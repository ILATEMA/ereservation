<?php

declare(strict_types=1);

namespace App\Core;

final class Session
{
    public static function demarrer(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start([
                'cookie_httponly' => true,
                'cookie_samesite'  => 'Lax',
            ]);
        }
    }

    public static function genererJetonCsrf(): string
    {
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf_token'];
    }

    public static function verifierJetonCsrf(?string $jetonRecu): bool
    {
        return is_string($jetonRecu)
            && is_string($_SESSION['_csrf_token'] ?? null)
            && hash_equals($_SESSION['_csrf_token'], $jetonRecu);
    }

    public static function admin(): ?int
    {
        return $_SESSION['admin_id'] ?? null;
    }

    public static function compagnie(): ?int
    {
        return $_SESSION['compagnie_id'] ?? null;
    }

    public static function connecterAdmin(int $id): void
    {
        session_regenerate_id(true);
        unset($_SESSION['compagnie_id']);
        $_SESSION['admin_id'] = $id;
    }

    public static function connecterCompagnie(int $id): void
    {
        session_regenerate_id(true);
        unset($_SESSION['admin_id']);
        $_SESSION['compagnie_id'] = $id;
    }

    public static function detruire(): void
    {
        $_SESSION = [];
        session_destroy();
    }
}
