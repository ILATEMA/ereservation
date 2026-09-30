<?php

declare(strict_types=1);

namespace App\Controllers\Compagnie;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Models\Commande;
use App\Models\Compagnie as CompagnieModel;
use App\Models\TableauDeBordCompagnie;

final class DashboardController
{
    public function index(Request $requete): Response
    {
        if (Session::compagnie() === null) {
            return Response::redirection('/compagnie/connexion');
        }

        Commande::expirerPerimees();

        $compagnieId = (int) Session::compagnie();
        $compagnie = CompagnieModel::trouver($compagnieId);

        if ($compagnie === null || $compagnie['statut'] !== 'active') {
            Session::detruire();
            return Response::redirection('/compagnie/connexion');
        }

        $voyageDemande = (int) $requete->parametreRequete('voyage', 0);
        $donnees = TableauDeBordCompagnie::donnees($compagnieId, $voyageDemande);
        $donnees['compagnie'] = $compagnie;
        $donnees['jetonCsrf'] = Session::genererJetonCsrf();
        $donnees['jetonCsrf'] = Session::genererJetonCsrf();

        return Response::vue('compagnie/tableau-de-bord', $donnees);
    }
}
