<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\Commande;
use App\Models\Voyage;

final class SiegeController
{
    public function index(Request $requete): Response
    {
        Commande::expirerPerimees();

        $voyageId = (int) $requete->parametreRequete('voyage', 0);
        $voyageLigne = Voyage::trouverAvecDetails($voyageId);

        if ($voyageLigne === null || !Voyage::estReservable($voyageLigne)) {
            return Response::redirection('/resultats');
        }

        $voyageAffichage = Voyage::formaterPourAffichage($voyageLigne);
        $siegesOccupes = Voyage::siegesOccupes($voyageId);

        return Response::vue('voyageur/siege', [
            'voyage'          => $voyageAffichage,
            'siegesOccupes'    => $siegesOccupes,
        ]);
    }
}
