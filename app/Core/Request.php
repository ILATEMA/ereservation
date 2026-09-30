<?php

declare(strict_types=1);

namespace App\Core;

final class Request
{
    private array $requete;
    private array $corpsForm;
    private array $corpsJson;
    private array $fichiers;
    public readonly string $methode;
    public readonly string $chemin;

    private function __construct(
        string $methode,
        string $chemin,
        array $requete,
        array $corpsForm,
        array $corpsJson,
        array $fichiers
    ) {
        $this->methode = $methode;
        $this->chemin = $chemin;
        $this->requete = $requete;
        $this->corpsForm = $corpsForm;
        $this->corpsJson = $corpsJson;
        $this->fichiers = $fichiers;
    }

    public static function depuisGlobales(): self
    {
        $methode = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        $cheminBrut = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';

        // Le front controller (public/index.php) est LE SEUL point
        // d'entrée : peu importe le sous-dossier où le projet est
        // installé (WampServer, sous-domaine, etc.), on retire le
        // préfixe correspondant au dossier de public/index.php.
        $prefixeBase = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
        if ($prefixeBase !== '' && str_starts_with($cheminBrut, $prefixeBase)) {
            $cheminBrut = substr($cheminBrut, strlen($prefixeBase));
        }
        $chemin = '/' . trim($cheminBrut, '/');

        $corpsJson = [];
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (str_contains($contentType, 'application/json')) {
            $decode = json_decode(file_get_contents('php://input') ?: '', true);
            if (is_array($decode)) {
                $corpsJson = $decode;
            }
        }

        return new self($methode, $chemin, $_GET, $_POST, $corpsJson, $_FILES);
    }

    public static function creerPourTest(string $methode, string $chemin, array $corpsJson = [], array $requete = []): self
    {
        return new self(strtoupper($methode), '/' . trim($chemin, '/'), $requete, [], $corpsJson, []);
    }


    public function entete(string $nom, ?string $defaut = null): ?string
    {
        $cle = 'HTTP_' . strtoupper(str_replace('-', '_', $nom));
        if (isset($_SERVER[$cle])) {
            return (string) $_SERVER[$cle];
        }
        if (strtolower($nom) === 'content-type' && isset($_SERVER['CONTENT_TYPE'])) {
            return (string) $_SERVER['CONTENT_TYPE'];
        }
        return $defaut;
    }

    /** Lit une valeur du corps (JSON prioritaire, sinon formulaire) */
    public function entree(string $cle, mixed $defaut = null): mixed
    {
        return $this->corpsJson[$cle] ?? $this->corpsForm[$cle] ?? $defaut;
    }

    public function parametreRequete(string $cle, mixed $defaut = null): mixed
    {
        return $this->requete[$cle] ?? $defaut;
    }

    public function fichier(string $cle): ?array
    {
        return $this->fichiers[$cle] ?? null;
    }

    /** @return array<string,mixed> */
    public function toutLeCorps(): array
    {
        return array_merge($this->corpsForm, $this->corpsJson);
    }
}
