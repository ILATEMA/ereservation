<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\Commande;
use App\Models\Voyage;

final class RechercheController
{
    public function index(Request $requete): Response
    {
        Commande::expirerPerimees();

        $depart = trim((string) $requete->parametreRequete('depart', ''));
        $arrivee = trim((string) $requete->parametreRequete('arrivee', ''));
        $date = trim((string) $requete->parametreRequete('date', ''));

        $voyages = Voyage::rechercher($depart, $arrivee, $date);

        return Response::vue('voyageur/resultats', [
            'voyages'  => $voyages,
            'critDepart' => $depart,
            'critArrivee' => $arrivee,
            'critDate'    => $date,
        ]);
    }
}
