<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Le routeur associe une URL (ex: '/compagnie/connexion') à un
 * contrôleur, peu importe où vivent les fichiers PHP sur le disque.
 *
 * Les routes restent définies avec un chemin logique commençant par /.
 * Les vues utilisent url_app() pour transformer ces chemins logiques en
 * URLs compatibles avec une installation dans un sous-dossier.
 */
final class Router
{
    /** @var array<int, array{methode:string, motif:string, gestionnaire:mixed, noms:string[]}> */
    private array $routes = [];

    public function ajouter(string $methode, string $chemin, mixed $gestionnaire): void
    {
        $chemin = '/' . trim($chemin, '/');
        $noms = [];

        $motif = preg_replace_callback(
            '#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#',
            function (array $m) use (&$noms): string {
                $noms[] = $m[1];
                return '([^/]+)';
            },
            $chemin
        );

        $this->routes[] = [
            'methode'      => strtoupper($methode),
            'motif'         => '#^' . $motif . '$#',
            'gestionnaire'   => $gestionnaire,
            'noms'           => $noms,
        ];
    }

    public function get(string $chemin, mixed $gestionnaire): void
    {
        $this->ajouter('GET', $chemin, $gestionnaire);
    }

    public function post(string $chemin, mixed $gestionnaire): void
    {
        $this->ajouter('POST', $chemin, $gestionnaire);
    }

    public function distribuer(Request $requete): Response
    {
        $uneRouteCorrespond = false;

        foreach ($this->routes as $route) {
            if (!preg_match($route['motif'], $requete->chemin, $correspondances)) {
                continue;
            }
            $uneRouteCorrespond = true;

            if ($route['methode'] !== $requete->methode) {
                continue;
            }

            array_shift($correspondances);
            $parametres = array_combine($route['noms'], $correspondances);

            [$classe, $methode] = $route['gestionnaire'];
            $controleur = new $classe();

            return $controleur->$methode($requete, $parametres);
        }

        if ($uneRouteCorrespond) {
            return Response::erreur('Méthode HTTP non autorisée pour cette route.', 405);
        }

        return Response::erreur("Route introuvable : {$requete->methode} {$requete->chemin}", 404);
    }
}
