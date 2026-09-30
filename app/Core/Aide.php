<?php

declare(strict_types=1);

namespace App\Core;

final class Aide
{
    public static function formaterFcfa(int $montant): string
    {
        return number_format($montant, 0, ',', ' ') . ' FCFA';
    }

    public static function formaterDuree(int $minutes): string
    {
        $heures = intdiv($minutes, 60);
        $reste = $minutes % 60;
        return $heures . 'h' . str_pad((string) $reste, 2, '0', STR_PAD_LEFT);
    }

    public static function genererCodeLisible(string $prefixe, int $longueur = 8): string
    {
        $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $suffixe = '';
        for ($i = 0; $i < $longueur; $i++) {
            $suffixe .= $alphabet[random_int(0, strlen($alphabet) - 1)];
        }
        return strtoupper($prefixe) . '-' . $suffixe;
    }

    /**
     * Nettoie un numéro de téléphone avant validation/stockage (retire
     * espaces/tirets) — sans ça, "+229 01 23 45 67 89" est rejeté alors
     * qu'il est parfaitement valide.
     */
    public static function nettoyerTelephone(string $telephone): string
    {
        return preg_replace('/[\s\-\.]+/', '', trim($telephone));
    }

    public static function urlBase(): string
    {
        $env = config('app.url');
        if ($env) {
            return rtrim($env, '/');
        }
        $schema = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $hote = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $dossier = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
        return "{$schema}://{$hote}{$dossier}";
    }
}
