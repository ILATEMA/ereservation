<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Models\Administrateur;

final class AuthController
{
    public function formulaire(Request $requete): Response
    {
        if (Session::admin() !== null) {
            return Response::redirection('/admin');
        }
        return Response::vue('admin/connexion', ['erreur' => null, 'jetonCsrf' => Session::genererJetonCsrf()]);
    }

    public function traiter(Request $requete): Response
    {
        if (!Session::verifierJetonCsrf((string) $requete->entree('csrf'))) {
            return Response::vue('admin/connexion', ['erreur' => 'Session expirée, merci de réessayer.', 'jetonCsrf' => Session::genererJetonCsrf()]);
        }

        $email = trim((string) $requete->entree('email', ''));
        $motDePasse = (string) $requete->entree('password', '');

        $admin = Administrateur::trouverParEmail($email);

        if ($admin !== false && $admin !== null && (int) $admin['actif'] === 1 && password_verify($motDePasse, $admin['mot_de_passe'])) {
            Session::connecterAdmin((int) $admin['id']);
            return Response::redirection('/admin');
        }

        return Response::vue('admin/connexion', [
            'erreur'    => 'ERR_AUTH · identifiants invalides ou compte non autorisé.',
            'jetonCsrf'  => Session::genererJetonCsrf(),
        ]);
    }
}
