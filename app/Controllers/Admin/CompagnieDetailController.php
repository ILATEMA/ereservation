<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Database;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

final class CompagnieDetailController
{
    public function index(Request $requete, array $parametres): Response
    {
        if (Session::admin() === null) {
            return Response::redirection('/admin/connexion');
        }

        $pdo = Database::connexion();
        $compagnieId = (int) ($parametres['id'] ?? 0);

        $stmt = $pdo->prepare(
            'SELECT id, nom, email, telephone, ville_siege, nombre_vehicules,
                    message_presentation, document_justificatif_chemin, statut, created_at
             FROM compagnies WHERE id = :id'
        );
        $stmt->execute(['id' => $compagnieId]);
        $compagnie = $stmt->fetch();

        if ($compagnie === false) {
            return Response::redirection('/admin');
        }

        $stmtAbo = $pdo->prepare('SELECT statut, date_debut_periode, date_fin_periode FROM abonnements_compagnie WHERE compagnie_id = :id');
        $stmtAbo->execute(['id' => $compagnieId]);
        $abonnementLigne = $stmtAbo->fetch();

        return Response::vue('admin/compagnie-detail', [
            'compagnie'   => $compagnie,
            'abonnement'   => $abonnementLigne !== false ? $abonnementLigne : null,
            'jetonCsrf'    => Session::genererJetonCsrf(),
            'jetonCsrf'    => Session::genererJetonCsrf(),
        ]);
    }
}
