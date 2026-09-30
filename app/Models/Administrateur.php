<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class Administrateur
{
    public static function trouverParEmail(string $email): ?array
    {
        $pdo = Database::connexion();
        $stmt = $pdo->prepare('SELECT id, nom, mot_de_passe, actif FROM administrateurs WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $ligne = $stmt->fetch();
        return $ligne !== false ? $ligne : null;
    }

    public static function trouver(int $id): ?array
    {
        $pdo = Database::connexion();
        $stmt = $pdo->prepare('SELECT id, nom, email FROM administrateurs WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $ligne = $stmt->fetch();
        return $ligne !== false ? $ligne : null;
    }
}
