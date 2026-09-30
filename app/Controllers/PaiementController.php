<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Session;
use App\Core\Response;
use App\Models\Commande;
use App\Models\Voyage;

final class PaiementController
{
    public function index(Request $requete): Response
    {
        $voyageId = (int) $requete->parametreRequete('voyage', 0);
        $voyageLigne = Voyage::trouverAvecDetails($voyageId);

        if ($voyageLigne === null) {
            return Response::redirection('/resultats');
        }

        return Response::vue('voyageur/paiement', [
            'voyage' => Voyage::formaterPourAffichage($voyageLigne),
            'jetonCsrf' => Session::genererJetonCsrf(),
        ]);
    }

    /**
     * Page sur laquelle FedaPay redirige le client après paiement.
     * Le webhook (confirmation serveur à serveur) est la seule vraie
     * source de vérité — cette page attend et se rafraîchit si besoin.
     */
    public function retour(Request $requete): Response
    {
        $codeCommande = trim((string) $requete->parametreRequete('commande', ''));

        if ($codeCommande === '') {
            return Response::redirection('/');
        }

        $commande = Commande::trouverParCode($codeCommande);

        if ($commande !== null && $commande['statut'] === 'payee') {
            return Response::redirection('/billet?commande=' . urlencode($codeCommande));
        }
        if ($commande === null || in_array($commande['statut'], ['expiree', 'annulee'], true)) {
            return Response::redirection('/');
        }

        return Response::vue('voyageur/paiement-retour', ['codeCommande' => $codeCommande]);
    }
}
