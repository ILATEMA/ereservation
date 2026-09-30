<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

final class Database
{
    private static ?PDO $instance = null;

    private function __construct()
    {
    }

    public static function connexion(): PDO
    {
        date_default_timezone_set('UTC');

        if (self::$instance === null) {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
                config('db.hote'),
                config('db.port'),
                config('db.nom')
            );

            // PHP 8.5 a déplacé PDO::MYSQL_ATTR_INIT_COMMAND vers
            // Pdo\Mysql::ATTR_INIT_COMMAND — on détecte dynamiquement
            // laquelle utiliser pour rester compatible PHP 8.2 -> 8.5+.
            $cleInitCommand = defined('Pdo\\Mysql::ATTR_INIT_COMMAND')
                ? constant('Pdo\\Mysql::ATTR_INIT_COMMAND')
                : PDO::MYSQL_ATTR_INIT_COMMAND;

            try {
                self::$instance = new PDO(
                    $dsn,
                    config('db.utilisateur'),
                    config('db.mot_de_passe'),
                    [
                        PDO::ATTR_ERRMODE           => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES   => false,
                        // UTC partout (base + PHP) pour éviter tout décalage
                        // entre calcul du hold en PHP et comparaison en SQL.
                        $cleInitCommand              => "SET time_zone = '+00:00'",
                    ]
                );
            } catch (PDOException $e) {
                if (config('app.debug')) {
                    throw $e; // trace complète utile en développement
                }
                http_response_code(500);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['erreur' => 'Connexion à la base de données impossible.']);
                exit;
            }
        }

        return self::$instance;
    }
}
