<?php

declare(strict_types=1);

namespace App\Controllers\Api\Compagnie;

use App\Core\ErreurMetier;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Models\VoyageCompagnie;
use App\Models\Compagnie;

final class VoyageController
{
    private function exigerCsrf(Request $requete): void
    {
        if (!Session::verifierJetonCsrf($requete->entree('csrf') ?? $requete->entete('X-CSRF-Token'))) {
            throw new ErreurMetier('Jeton CSRF invalide ou session expirée.', 419);
        }
    }

    public function creer(Request $requete): Response
    {
        $this->exigerCsrf($requete);
        $compagnieId = Session::compagnie();
        if ($compagnieId === null || !Compagnie::estActive((int) $compagnieId)) {
            throw new ErreurMetier('Non autorisé.', 401);
        }

        $resultat = VoyageCompagnie::creer(
            (int) $compagnieId,
            (int) $requete->entree('villeDepartId', 0),
            (int) $requete->entree('villeArriveeId', 0),
            (int) $requete->entree('dureeMinutes', 0),
            trim((string) $requete->entree('gareDepart', '')) ?: null,
            (string) $requete->entree('date', ''),
            (string) $requete->entree('heure', ''),
            (int) $requete->entree('prixNetFcfa', 0),
            (string) $requete->entree('typeVehicule', 'standard')
        );

        return Response::json(array_merge(['succes' => true], $resultat), 201);
    }
}
