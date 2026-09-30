<?php

declare(strict_types=1);

namespace App\Controllers\Compagnie;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Models\Compagnie;

final class AuthController
{
    public function formulaire(Request $requete): Response
    {
        if (Session::compagnie() !== null) {
            return Response::redirection('/compagnie');
        }
        return Response::vue('compagnie/connexion', ['erreur' => null, 'jetonCsrf' => Session::genererJetonCsrf()]);
    }

    public function traiter(Request $requete): Response
    {
        if (!Session::verifierJetonCsrf((string) $requete->entree('csrf'))) {
            return Response::vue('compagnie/connexion', ['erreur' => 'Session expirée, merci de réessayer.', 'jetonCsrf' => Session::genererJetonCsrf()]);
        }

        $email = trim((string) $requete->entree('email', ''));
        $motDePasse = (string) $requete->entree('password', '');

        $compagnie = Compagnie::trouverParEmail($email);
        $erreur = null;

        if ($compagnie !== null && password_verify($motDePasse, $compagnie['mot_de_passe'])) {
            if ($compagnie['statut'] === 'en_attente_validation') {
                $erreur = 'Votre compte est en attente de validation par notre équipe.';
            } elseif (in_array($compagnie['statut'], ['rejetee', 'desactivee'], true)) {
                $erreur = 'Ce compte n\'est plus actif. Contactez le support.';
            } else {
                Session::connecterCompagnie((int) $compagnie['id']);
                return Response::redirection('/compagnie');
            }
        } else {
            $erreur = 'Identifiants incorrects. Vérifiez votre email et votre mot de passe.';
        }

        return Response::vue('compagnie/connexion', ['erreur' => $erreur, 'jetonCsrf' => Session::genererJetonCsrf()]);
    }
}
