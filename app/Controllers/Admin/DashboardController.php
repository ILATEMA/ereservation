<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Models\Administrateur;
use App\Models\Commande;
use App\Models\TableauDeBordAdmin;

final class DashboardController
{
    public function index(Request $requete): Response
    {
        if (Session::admin() === null) {
            return Response::redirection('/admin/connexion');
        }

        Commande::expirerPerimees();

        $admin = Administrateur::trouver((int) Session::admin());
        $nomAdmin = $admin['nom'] ?? 'Administrateur';

        $donnees = TableauDeBordAdmin::donnees();
        $donnees['nomAdmin'] = $nomAdmin;

        return Response::vue('admin/tableau-de-bord', $donnees);
    }
}
