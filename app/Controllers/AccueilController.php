<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;

final class AccueilController
{
    public function index(Request $requete): Response
    {
        return Response::vue('voyageur/accueil');
    }
}
