<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Aide;
use App\Core\Database;
use App\Core\ErreurMetier;
use PDO;
use PDOException;

final class Commande
{
    public static function expirerPerimees(): void
    {
        $pdo = Database::connexion();
        // Ordre important : expirer les réservations avant les commandes,
        // en s'appuyant sur l'échéance de LEUR commande.
        $pdo->exec(
            "UPDATE reservations r
             INNER JOIN commandes c ON c.id = r.commande_id
             SET r.statut = 'expiree'
             WHERE c.statut = 'en_attente_paiement'
               AND c.expire_a < UTC_TIMESTAMP()
               AND r.statut = 'en_attente_paiement'"
        );
        $pdo->exec(
            "UPDATE commandes SET statut = 'expiree'
             WHERE statut = 'en_attente_paiement' AND expire_a < UTC_TIMESTAMP()"
        );
    }

    /**
     * Crée une commande de 1 à 4 sièges, en hold pendant
     * config('metier.hold_minutes'). Tout ou rien : si un seul siège
     * est indisponible, toute la commande est annulée.
     *
     * @param int[] $sieges
     * @param string[] $passagers
     * @throws ErreurMetier
     */
    public static function creer(
        int $voyageId,
        array $sieges,
        array $passagers,
        string $telephoneContact,
        ?string $emailContact
    ): array {
        self::expirerPerimees();
        $pdo = Database::connexion();

        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare(
                'SELECT v.id, v.prix_fcfa, v.statut, v.date_heure_depart, c.statut AS compagnie_statut
                 FROM voyages v
                 INNER JOIN compagnies c ON c.id = v.compagnie_id
                 WHERE v.id = :id FOR UPDATE'
            );
            $stmt->execute(['id' => $voyageId]);
            $voyage = $stmt->fetch();

            if ($voyage === false) {
                throw new ErreurMetier("Voyage #{$voyageId} introuvable.", 404);
            }

            $estReservable = $voyage['statut'] === 'planifie'
                && $voyage['compagnie_statut'] === 'active'
                && strtotime($voyage['date_heure_depart']) > time();

            if (!$estReservable) {
                throw new ErreurMetier('Ce voyage n\'accepte plus de réservations (complet, annulé ou déjà parti).', 409);
            }

            $stmtOccupes = $pdo->prepare(
                "SELECT siege_numero FROM reservations
                 WHERE voyage_id = :id AND statut IN ('en_attente_paiement','confirmee')"
            );
            $stmtOccupes->execute(['id' => $voyageId]);
            $siegesOccupes = array_map('intval', $stmtOccupes->fetchAll(PDO::FETCH_COLUMN));

            $conflits = array_values(array_intersect($sieges, $siegesOccupes));
            if ($conflits !== []) {
                throw new ErreurMetier(
                    'Un ou plusieurs sièges viennent d\'être pris par un autre voyageur.',
                    409,
                    array_map(fn($s) => "Siège {$s} indisponible", $conflits)
                );
            }

            $prixUnitaire = (int) $voyage['prix_fcfa'];
            $montantTotal = $prixUnitaire * count($sieges);
            $maintenant = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
            $expireA = $maintenant->modify('+' . config('metier.hold_minutes') . ' minutes');
            $codeCommande = Aide::genererCodeLisible('CMD');

            $pdo->prepare(
                'INSERT INTO commandes
                    (code_commande, voyage_id, nombre_sieges, montant_total_fcfa, telephone_contact, email_contact, statut, expire_a)
                 VALUES (:code, :vid, :nb, :montant, :tel, :email, "en_attente_paiement", :expire)'
            )->execute([
                'code'    => $codeCommande,
                'vid'      => $voyageId,
                'nb'       => count($sieges),
                'montant'   => $montantTotal,
                'tel'       => $telephoneContact,
                'email'     => $emailContact,
                'expire'    => $expireA->format('Y-m-d H:i:s'),
            ]);
            $commandeId = (int) $pdo->lastInsertId();

            $stmtReservation = $pdo->prepare(
                'INSERT INTO reservations
                    (commande_id, voyage_id, siege_numero, statut, code_reservation, nom_passager, telephone_passager, email_passager, prix_paye_fcfa, expire_a)
                 VALUES (:cid, :vid, :siege, "en_attente_paiement", :code, :nom, :tel, :email, :prix, :expire)'
            );

            $reservationsCreees = [];
            foreach ($sieges as $index => $siegeNumero) {
                $codeReservation = Aide::genererCodeLisible('RES');
                try {
                    $stmtReservation->execute([
                        'cid'    => $commandeId,
                        'vid'     => $voyageId,
                        'siege'   => $siegeNumero,
                        'code'    => $codeReservation,
                        'nom'     => trim((string) $passagers[$index]),
                        'tel'      => $telephoneContact,
                        'email'    => $emailContact,
                        'prix'     => $prixUnitaire,
                        'expire'    => $expireA->format('Y-m-d H:i:s'),
                    ]);
                } catch (PDOException $e) {
                    if ($e->getCode() === '23000') {
                        throw new ErreurMetier(
                            "Le siège {$siegeNumero} vient d'être pris par un autre voyageur, merci de recommencer.",
                            409
                        );
                    }
                    throw $e;
                }
                $reservationsCreees[] = ['siege' => $siegeNumero, 'codeReservation' => $codeReservation];
            }

            $pdo->commit();

            return [
                'codeCommande'  => $codeCommande,
                'voyageId'       => $voyageId,
                'sieges'          => $reservationsCreees,
                'montantTotal'     => $montantTotal,
                'expireA'           => $expireA->format('Y-m-d H:i:s'),
            ];
        } catch (\Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    public static function trouverParCode(string $code): ?array
    {
        $pdo = Database::connexion();
        $stmt = $pdo->prepare('SELECT * FROM commandes WHERE code_commande = :code');
        $stmt->execute(['code' => $code]);
        $ligne = $stmt->fetch();
        return $ligne !== false ? $ligne : null;
    }
}
