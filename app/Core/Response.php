<?php

declare(strict_types=1);

namespace App\Core;

final class Response
{
    private function __construct(
        public readonly int $codeStatut,
        public readonly string $corps,
        public readonly string $typeContenu,
        public readonly ?string $redirection = null,
    ) {
    }

    public static function json(mixed $donnees, int $codeStatut = 200): self
    {
        return new self(
            $codeStatut,
            json_encode($donnees, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE),
            'application/json; charset=utf-8'
        );
    }

    public static function erreur(string $message, int $codeStatut, array $details = []): self
    {
        $corps = ['erreur' => $message];
        if ($details !== []) {
            $corps['details'] = $details;
        }
        return self::json($corps, $codeStatut);
    }

    public static function html(string $html, int $codeStatut = 200): self
    {
        return new self($codeStatut, $html, 'text/html; charset=utf-8');
    }

    /**
     * Affiche une vue PHP (app/Views/...) avec les variables fournies,
     * et enveloppe le résultat dans une Response HTML.
     */
    public static function vue(string $nomVue, array $donnees = [], int $codeStatut = 200): self
    {
        $chemin = dirname(__DIR__) . '/Views/' . $nomVue . '.php';

        if (!file_exists($chemin)) {
            throw new \RuntimeException("Vue introuvable : {$nomVue} ({$chemin})");
        }

        extract($donnees);
        ob_start();
        require $chemin;
        $html = ob_get_clean();

        return self::html($html, $codeStatut);
    }

    public static function redirection(string $chemin): self
    {
        if (str_starts_with($chemin, '/') && !str_starts_with($chemin, '//')) {
            $chemin = url_app($chemin);
        }
        return new self(302, '', 'text/html; charset=utf-8', $chemin);
    }

    public function envoyer(): void
    {
        if ($this->redirection !== null) {
            header('Location: ' . $this->redirection);
            return;
        }
        http_response_code($this->codeStatut);
        header('Content-Type: ' . $this->typeContenu);
        echo $this->corps;
    }
}
