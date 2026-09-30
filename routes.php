<?php

declare(strict_types=1);

/**
 * routes.php
 * ============================================================
 * TOUTES les routes du site, dans un seul fichier lisible.
 * "Quelle page gère quelle URL ?" -> la réponse est ici, jamais
 * ailleurs. Chaque ligne se lit : méthode, URL, [Contrôleur, action].
 * ============================================================
 */

use App\Controllers\AccueilController;
use App\Controllers\Admin\AuthController as AdminAuthController;
use App\Controllers\Admin\CompagnieDetailController;
use App\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Controllers\Api\Admin\CompagnieActionController;
use App\Controllers\Api\Compagnie\CompteController as ApiCompagnieCompteController;
use App\Controllers\Api\Compagnie\VoyageController as ApiCompagnieVoyageController;
use App\Controllers\Api\CommandeController;
use App\Controllers\Api\FedaPayController;
use App\Controllers\BilletController;
use App\Controllers\CompagnieRejoindreController;
use App\Controllers\Compagnie\AuthController as CompagnieAuthController;
use App\Controllers\Compagnie\DashboardController as CompagnieDashboardController;
use App\Controllers\DeconnexionController;
use App\Controllers\PaiementController;
use App\Controllers\RechercheController;
use App\Controllers\SiegeController;
use App\Core\Router;

return function (Router $router): void {

    // ---------------------------------------------------------------
    // Parcours voyageur (public, sans compte)
    // ---------------------------------------------------------------
    $router->get('/', [AccueilController::class, 'index']);
    $router->get('/resultats', [RechercheController::class, 'index']);
    $router->get('/siege', [SiegeController::class, 'index']);
    $router->get('/paiement', [PaiementController::class, 'index']);
    $router->get('/paiement/retour', [PaiementController::class, 'retour']);
    $router->get('/billet', [BilletController::class, 'index']);

    // ---------------------------------------------------------------
    // Compagnie — inscription (publique)
    // ---------------------------------------------------------------
    $router->get('/rejoindre', [CompagnieRejoindreController::class, 'formulaire']);
    $router->post('/rejoindre', [CompagnieRejoindreController::class, 'traiter']);

    // ---------------------------------------------------------------
    // Compagnie — espace connecté
    // ---------------------------------------------------------------
    $router->get('/compagnie/connexion', [CompagnieAuthController::class, 'formulaire']);
    $router->post('/compagnie/connexion', [CompagnieAuthController::class, 'traiter']);
    $router->get('/compagnie', [CompagnieDashboardController::class, 'index']);

    // ---------------------------------------------------------------
    // Admin — espace connecté
    // ---------------------------------------------------------------
    $router->get('/admin/connexion', [AdminAuthController::class, 'formulaire']);
    $router->post('/admin/connexion', [AdminAuthController::class, 'traiter']);
    $router->get('/admin', [AdminDashboardController::class, 'index']);
    $router->get('/admin/compagnies/{id}', [CompagnieDetailController::class, 'index']);

    // ---------------------------------------------------------------
    // Déconnexion (commune)
    // ---------------------------------------------------------------
    $router->get('/deconnexion', [DeconnexionController::class, 'index']);

    // ---------------------------------------------------------------
    // API — appelée en JS (fetch), jamais visitée directement au navigateur
    // ---------------------------------------------------------------
    $router->post('/api/commandes', [CommandeController::class, 'creer']);
    $router->post('/api/fedapay/initier', [FedaPayController::class, 'initier']);
    $router->post('/api/fedapay/webhook', [FedaPayController::class, 'webhook']);

    $router->post('/api/admin/compagnies/{id}/valider', [CompagnieActionController::class, 'valider']);
    $router->post('/api/admin/compagnies/{id}/rejeter', [CompagnieActionController::class, 'rejeter']);
    $router->post('/api/admin/compagnies/{id}/desactiver', [CompagnieActionController::class, 'desactiver']);
    $router->post('/api/admin/compagnies/{id}/reactiver', [CompagnieActionController::class, 'reactiver']);
    $router->post('/api/admin/compagnies/{id}/supprimer', [CompagnieActionController::class, 'supprimer']);

    $router->post('/api/compagnie/voyages', [ApiCompagnieVoyageController::class, 'creer']);
    $router->post('/api/compagnie/mot-de-passe', [ApiCompagnieCompteController::class, 'changerMotDePasse']);
};
