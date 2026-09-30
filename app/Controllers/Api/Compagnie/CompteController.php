<?php

declare(strict_types=1);

namespace App\Controllers\Api\Compagnie;

use App\Core\ErreurMetier;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Models\Compagnie;

final class CompteController
{
    private function exigerCsrf(Request $requete): void
    {
        if (!Session::verifierJetonCsrf($requete->entree('csrf') ?? $requete->entete('X-CSRF-Token'))) {
            throw new ErreurMetier('Jeton CSRF invalide ou session expirée.', 419);
        }
    }

    public function changerMotDePasse(Request $requete): Response
    {
        $this->exigerCsrf($requete);
        $compagnieId = Session::compagnie();
        if ($compagnieId === null || !Compagnie::estActive((int) $compagnieId)) {
            throw new ErreurMetier('Non autorisé.', 401);
        }

        $nouveau = (string) $requete->entree('nouveauMotDePasse', '');
        if (strlen($nouveau) < 8
            || !preg_match('/[A-Z]/', $nouveau)
            || !preg_match('/[0-9]/', $nouveau)
            || !preg_match('/[^A-Za-z0-9]/', $nouveau)
        ) {
            throw new ErreurMetier('Le nouveau mot de passe doit contenir au moins 8 caractères, une majuscule, un chiffre et un caractère spécial.', 422);
        }

        Compagnie::changerMotDePasse(
            (int) $compagnieId,
            (string) $requete->entree('motDePasseActuel', ''),
            $nouveau
        );

        return Response::json(['succes' => true]);
    }
}
