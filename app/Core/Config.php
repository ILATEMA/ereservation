<?php

declare(strict_types=1);

/**
 * Lit une valeur de config/env.php avec la notation pointée.
 *
 * Exemples :
 *   config('db.hote')                     -> '127.0.0.1'
 *   config('metier.frais_service_fcfa')     -> 250
 *   config('smtp.utilisateur')               -> 'TON-ADRESSE@gmail.com'
 *
 * @param string $cle     Chemin en notation pointée (ex: 'db.hote')
 * @param mixed  $defaut  Valeur retournée si la clé n'existe pas
 */
function config(string $cle, mixed $defaut = null): mixed
{
    static $valeurs = null;

    if ($valeurs === null) {
        $valeurs = require dirname(__DIR__, 2) . '/config/env.php';
    }

    $segments = explode('.', $cle);
    $courant = $valeurs;

    foreach ($segments as $segment) {
        if (!is_array($courant) || !array_key_exists($segment, $courant)) {
            return $defaut;
        }
        $courant = $courant[$segment];
    }

    return $courant;
}


function url_app(string $chemin = '/'): string
{
    $base = rtrim(\App\Core\Aide::urlBase(), '/');
    $chemin = '/' . ltrim($chemin, '/');
    return $base . ($chemin === '/' ? '' : $chemin);
}
