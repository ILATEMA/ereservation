<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

final class DeconnexionController
{
    public function index(Request $requete): Response
    {
        $espace = $requete->parametreRequete('espace', Session::admin() !== null ? 'admin' : 'compagnie');
        Session::detruire();
        return Response::redirection($espace === 'admin' ? '/admin/connexion' : '/compagnie/connexion');
    }
}
