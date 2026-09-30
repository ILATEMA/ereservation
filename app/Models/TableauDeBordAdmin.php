<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class TableauDeBordAdmin
{
    public static function donnees(): array
    {
        $pdo = Database::connexion();
        $frais = (int) config('metier.frais_service_fcfa');

        $nbCompagniesActives = (int) $pdo->query("SELECT COUNT(*) FROM compagnies WHERE statut = 'active'")->fetchColumn();
        $nbCompagniesEnEssai = (int) $pdo->query("SELECT COUNT(*) FROM abonnements_compagnie WHERE statut = 'essai'")->fetchColumn();
        $nbCompagniesEnAttente = (int) $pdo->query("SELECT COUNT(*) FROM compagnies WHERE statut = 'en_attente_validation'")->fetchColumn();

        $billetsVendusMois = (int) $pdo->query(
            "SELECT COUNT(*) FROM reservations
             WHERE statut = 'confirmee' AND MONTH(created_at) = MONTH(UTC_TIMESTAMP()) AND YEAR(created_at) = YEAR(UTC_TIMESTAMP())"
        )->fetchColumn();

        $revenuFraisMois = $billetsVendusMois * $frais;

        $voyageursUniques = (int) $pdo->query(
            "SELECT COUNT(DISTINCT telephone_passager) FROM reservations
             WHERE statut = 'confirmee' AND MONTH(created_at) = MONTH(UTC_TIMESTAMP()) AND YEAR(created_at) = YEAR(UTC_TIMESTAMP())"
        )->fetchColumn();

        $compagnies = $pdo->query(
            "SELECT c.id, c.nom, c.statut, c.created_at,
                    a.statut AS statut_abonnement, a.date_fin_periode,
                    (SELECT COUNT(*) FROM voyages v WHERE v.compagnie_id = c.id AND v.statut = 'planifie') AS voyages_actifs
             FROM compagnies c
             LEFT JOIN abonnements_compagnie a ON a.compagnie_id = c.id
             ORDER BY c.created_at DESC"
        )->fetchAll();

        return compact(
            'nbCompagniesActives', 'nbCompagniesEnEssai', 'nbCompagniesEnAttente',
            'billetsVendusMois', 'revenuFraisMois', 'voyageursUniques', 'compagnies'
        );
    }

    public static function initiales(string $nom): string
    {
        $mots = preg_split('/\s+/', trim($nom));
        $init = '';
        foreach (array_slice($mots, 0, 2) as $m) {
            $init .= mb_strtoupper(mb_substr($m, 0, 1));
        }
        return $init !== '' ? $init : '??';
    }

    /** @return array{0:string,1:string} [classeCss, libellé] */
    public static function badgeStatutCompagnie(array $c): array
    {
        if ($c['statut'] === 'en_attente_validation') {
            return ['badge-muted', 'En attente de validation'];
        }
        if ($c['statut'] === 'rejetee') {
            return ['badge-error', 'Rejetée'];
        }
        if ($c['statut'] === 'desactivee') {
            return ['badge-error', 'Désactivée'];
        }
        return match ($c['statut_abonnement']) {
            'essai'          => ['badge-warn', 'Essai gratuit'],
            'actif'           => ['badge-success', 'Actif'],
            'grace'           => ['badge-warn', 'En grâce'],
            'lecture_seule'    => ['badge-error', 'Lecture seule'],
            default            => ['badge-muted', 'Actif (abonnement à configurer)'],
        };
    }
}
