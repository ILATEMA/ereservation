<?php

declare(strict_types=1);

/**
 * public/index.php — LE seul fichier exposé par le serveur web.
 * Tout le reste (app/, config/, routes.php) est en dehors de public/,
 * donc jamais accessible directement par une URL.
 */

// --- Auto-chargement (pas de Composer nécessaire) ---------------------
spl_autoload_register(function (string $classe): void {
    $prefixe = 'App\\';
    if (!str_starts_with($classe, $prefixe)) {
        return;
    }
    $cheminRelatif = str_replace('\\', '/', substr($classe, strlen($prefixe)));
    $chemin = dirname(__DIR__) . '/app/' . $cheminRelatif . '.php';
    if (file_exists($chemin)) {
        require $chemin;
    }
});

require dirname(__DIR__) . '/app/Core/Config.php';

use App\Core\GestionnaireErreurs;
use App\Core\Request;
use App\Core\Router;
use App\Core\Session;

if (config('app.debug')) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
}

Session::demarrer();

$router = new Router();
(require dirname(__DIR__) . '/routes.php')($router);

$requete = Request::depuisGlobales();

try {
    $reponse = $router->distribuer($requete);
} catch (\Throwable $exception) {
    $reponse = GestionnaireErreurs::versReponse($exception);
}

$reponse->envoyer();
