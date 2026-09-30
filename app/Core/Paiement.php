<?php

declare(strict_types=1);

namespace App\Core;

use App\Models\Commande as CommandeModel;

/**
 * Orchestre la confirmation d'un paiement — que ce soit en mode démo
 * (FedaPay pas encore configuré) ou en vrai (webhook FedaPay).
 */
final class Paiement
{
    /**
     * MODE DÉMO : simule un paiement toujours réussi. Actif tant que
     * config('fedapay.cle_secrete') est vide — bascule automatiquement
     * vers le vrai FedaPay dès qu'une clé est renseignée, sans rien
     * changer au code.
     */
    public static function simuler(string $codeCommande): void
    {
        CommandeModel::expirerPerimees();
   
        $pdo = Database::connexion();
        $commande = CommandeModel::trouverParCode($codeCommande);

        if ($commande === null) {
            throw new ErreurMetier('Commande introuvable.', 404);
        }
        if ($commande['statut'] === 'payee') {
            return; // déjà traité, idempotent
        }
        if ($commande['statut'] !== 'en_attente_paiement') {
            throw new ErreurMetier('Le délai de 15 minutes est dépassé, merci de recommencer votre réservation.', 410);
        }

        $pdo->beginTransaction();
        try {
            $reference = 'SIMULATION-' . strtoupper(bin2hex(random_bytes(6)));

            $pdo->prepare(
                'INSERT INTO paiements (commande_id, fournisseur, reference_transaction, montant_fcfa, statut, donnees_brutes)
                 VALUES (:cid, "fedapay", :ref, :montant, "reussi", :donnees)'
            )->execute([
                'cid'     => $commande['id'],
                'ref'      => $reference,
                'montant'   => (int) $commande['montant_total_fcfa'],
                'donnees'   => json_encode(['simulation' => true, 'note' => 'FedaPay non configuré — paiement simulé'], JSON_UNESCAPED_UNICODE),
            ]);

            $pdo->prepare('UPDATE commandes SET statut = "payee" WHERE id = :id')->execute(['id' => $commande['id']]);
            $pdo->prepare('UPDATE reservations SET statut = "confirmee" WHERE commande_id = :id')->execute(['id' => $commande['id']]);

            $pdo->commit();
        } catch (\Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    /**
     * Confirmation RÉELLE, appelée uniquement par le webhook FedaPay
     * après revérification de la transaction auprès de l'API (jamais
     * en se fiant au seul contenu du webhook).
     */
    public static function confirmerReel(int $transactionId, array $transactionVerifiee): void
    {
        $pdo = Database::connexion();
        $pdo->beginTransaction();

        try {
            $stmt = $pdo->prepare(
                'SELECT id, statut, montant_total_fcfa FROM commandes WHERE fedapay_transaction_id = :tid FOR UPDATE'
            );
            $stmt->execute(['tid' => $transactionId]);
            $commande = $stmt->fetch();

            if ($commande === false || $commande['statut'] === 'payee') {
                $pdo->commit();
                return;
            }

            if (($transactionVerifiee['status'] ?? null) !== 'approved') {
                error_log("[paiement] Transaction #{$transactionId} non approuvée, commande laissée en attente.");
                $pdo->commit();
                return;
            }

            $montantTransaction = (int) ($transactionVerifiee['amount'] ?? -1);
            if ($montantTransaction !== (int) $commande['montant_total_fcfa']) {
                throw new ErreurMetier('Le montant de la transaction FedaPay ne correspond pas à la commande.', 409);
            }

            $pdo->prepare(
                'INSERT INTO paiements (commande_id, fournisseur, reference_transaction, montant_fcfa, statut, donnees_brutes)
                 VALUES (:cid, "fedapay", :ref, :montant, "reussi", :donnees)
                 ON DUPLICATE KEY UPDATE statut = "reussi", montant_fcfa = VALUES(montant_fcfa), donnees_brutes = VALUES(donnees_brutes)'
            )->execute([
                'cid'      => $commande['id'],
                'ref'      => 'FEDAPAY-' . $transactionId,
                'montant'  => $montantTransaction,
                'donnees'  => json_encode($transactionVerifiee, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE),
            ]);

            $pdo->prepare('UPDATE commandes SET statut = "payee" WHERE id = :id')
                ->execute(['id' => $commande['id']]);
            $pdo->prepare('UPDATE reservations SET statut = "confirmee" WHERE commande_id = :id')
                ->execute(['id' => $commande['id']]);

            $pdo->commit();
        } catch (\Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }
}
