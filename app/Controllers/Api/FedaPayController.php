<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Core\Aide;
use App\Core\Database;
use App\Core\ErreurMetier;
use App\Core\FedaPay;
use App\Core\Paiement;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Models\Commande;

final class FedaPayController
{
    private function exigerCsrf(Request $requete): void
    {
        if (!Session::verifierJetonCsrf($requete->entete('X-CSRF-Token'))) {
            throw new ErreurMetier('Jeton CSRF invalide ou session expirée.', 419);
        }
    }

    public function initier(Request $requete): Response
    {
        $this->exigerCsrf($requete);
        
        Commande::expirerPerimees();
        $codeCommande = trim((string) $requete->entree('codeCommande', ''));
        if ($codeCommande === '') {
            throw new ErreurMetier('codeCommande manquant.', 422);
        }

        $commande = Commande::trouverParCode($codeCommande);
        if ($commande === null) {
            throw new ErreurMetier('Commande introuvable.', 404);
        }
        if ($commande['statut'] === 'payee') {
            throw new ErreurMetier('Cette commande est déjà payée.', 409);
        }
        if ($commande['statut'] !== 'en_attente_paiement') {
            throw new ErreurMetier('Le délai de 15 minutes est dépassé, merci de recommencer votre réservation.', 410);
        }

        // MODE DÉMO : FedaPay pas encore configuré -> paiement simulé.
        if (!FedaPay::estConfigure()) {
            Paiement::simuler($codeCommande);
            return Response::json(['checkoutUrl' => Aide::urlBase() . '/paiement/retour?commande=' . urlencode($codeCommande)]);
        }

        $pdo = Database::connexion();

        // Réutilise la transaction existante si déjà créée (deuxième clic).
        if ($commande['fedapay_transaction_id'] !== null) {
            $lien = FedaPay::genererLienPaiement((int) $commande['fedapay_transaction_id']);
            if ($lien['succes']) {
                return Response::json(['checkoutUrl' => $lien['url']]);
            }
        }

        $stmtNom = $pdo->prepare('SELECT nom_passager FROM reservations WHERE commande_id = :id LIMIT 1');
        $stmtNom->execute(['id' => $commande['id']]);
        $nomPassager = $stmtNom->fetchColumn() ?: 'Voyageur';

        $callbackUrl = Aide::urlBase() . '/paiement/retour?commande=' . urlencode($codeCommande);

        $resultatTransaction = FedaPay::creerTransaction(
            (int) $commande['montant_total_fcfa'],
            'Billet e-réservation — ' . $codeCommande,
            $nomPassager,
            $commande['email_contact'],
            $commande['telephone_contact'],
            $callbackUrl
        );

        if (!$resultatTransaction['succes']) {
            throw new ErreurMetier('Impossible de contacter FedaPay : ' . $resultatTransaction['erreur'], 502);
        }

        $transactionId = $resultatTransaction['transactionId'];
        $pdo->prepare('UPDATE commandes SET fedapay_transaction_id = :tid WHERE id = :id')
            ->execute(['tid' => $transactionId, 'id' => $commande['id']]);

        $lien = FedaPay::genererLienPaiement($transactionId);
        if (!$lien['succes']) {
            throw new ErreurMetier('Impossible de générer le lien de paiement : ' . $lien['erreur'], 502);
        }

        return Response::json(['checkoutUrl' => $lien['url']]);
    }

    public function webhook(Request $requete): Response
    {
        $corpsBrut = file_get_contents('php://input') ?: '';
        $signature = $_SERVER['HTTP_X_FEDAPAY_SIGNATURE'] ?? null;

        if (!FedaPay::verifierSignatureWebhook($corpsBrut, $signature)) {
            error_log('[fedapay-webhook] Signature invalide ou absente.');
            return Response::erreur('Signature invalide.', 401);
        }

        $payload = json_decode($corpsBrut, true);
        $transactionId = (int) ($payload['entity']['id'] ?? $payload['id'] ?? 0);

        if ($transactionId <= 0) {
            return Response::erreur('Payload sans id de transaction exploitable.', 400);
        }

        $resultat = FedaPay::recupererTransaction($transactionId);
        if (!$resultat['succes'] || $resultat['transaction'] === null) {
            error_log('[fedapay-webhook] Revérification impossible : ' . ($resultat['erreur'] ?? ''));
            return Response::erreur('Revérification impossible.', 502);
        }

        Paiement::confirmerReel($transactionId, $resultat['transaction']);

        return Response::json(['succes' => true]);
    }
}
