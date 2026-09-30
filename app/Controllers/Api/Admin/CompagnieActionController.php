<?php

declare(strict_types=1);

namespace App\Controllers\Api\Admin;

use App\Core\ErreurMetier;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Models\Compagnie;

final class CompagnieActionController
{
    private function exigerCsrf(Request $requete): void
    {
        if (!Session::verifierJetonCsrf($requete->entree('csrf') ?? $requete->entete('X-CSRF-Token'))) {
            throw new ErreurMetier('Jeton CSRF invalide ou session expirée.', 419);
        }
    }

    private function exigerAdmin(): int
    {
        $adminId = Session::admin();
        if ($adminId === null) {
            throw new ErreurMetier('Non autorisé.', 401);
        }
        return (int) $adminId;
    }

    public function valider(Request $requete, array $parametres): Response
    {
        $this->exigerCsrf($requete);
        $adminId = $this->exigerAdmin();
        $validation = Compagnie::valider((int) $parametres['id'], $adminId);
        return Response::json(array_merge(['succes' => true, 'statut' => 'active'], $validation));
    }

    public function rejeter(Request $requete, array $parametres): Response
    {
        $this->exigerCsrf($requete);
        $this->exigerAdmin();
        Compagnie::rejeter((int) $parametres['id']);
        return Response::json(['succes' => true, 'statut' => 'rejetee']);
    }

    public function desactiver(Request $requete, array $parametres): Response
    {
        $this->exigerCsrf($requete);
        $this->exigerAdmin();
        Compagnie::desactiver((int) $parametres['id']);
        return Response::json(['succes' => true, 'statut' => 'desactivee']);
    }

    public function reactiver(Request $requete, array $parametres): Response
    {
        $this->exigerCsrf($requete);
        $this->exigerAdmin();
        Compagnie::reactiver((int) $parametres['id']);
        return Response::json(['succes' => true, 'statut' => 'active']);
    }

    public function supprimer(Request $requete, array $parametres): Response
    {
        $this->exigerCsrf($requete);
        $this->exigerAdmin();
        Compagnie::supprimer((int) $parametres['id']);
        return Response::json(['succes' => true, 'action' => 'supprimee']);
    }
}
