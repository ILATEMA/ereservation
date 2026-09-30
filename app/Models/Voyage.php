<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Aide;
use App\Core\Database;
use PDO;

final class Voyage
{
    private const LIBELLES_TYPE = [
        'standard'      => 'Standard',
        'climatise'      => 'Climatisé',
        'climatise_vip'   => 'Climatisé VIP',
    ];

    /**
     * Recherche des voyages disponibles. La comparaison sur les villes
     * est insensible à la casse et aux accents grâce à la collation
     * utf8mb4_unicode_ci déjà utilisée sur la table `villes`.
     *
     * @return array<int, array<string,mixed>>
     */
    public static function rechercher(string $depart, string $arrivee, string $date): array
    {
        $pdo = Database::connexion();

        $sql = "SELECT
                    v.id, v.prix_fcfa, v.type_vehicule, v.date_heure_depart,
                    c.nom AS compagnie_nom,
                    vd.nom AS ville_depart, va.nom AS ville_arrivee,
                    i.duree_estimee_minutes, i.gare_depart,
                    v.places_totales - (
                        SELECT COUNT(*) FROM reservations r
                        WHERE r.voyage_id = v.id AND r.statut IN ('en_attente_paiement','confirmee')
                    ) AS places_restantes
                FROM voyages v
                INNER JOIN compagnies c   ON c.id = v.compagnie_id
                INNER JOIN itineraires i  ON i.id = v.itineraire_id
                INNER JOIN villes vd      ON vd.id = i.ville_depart_id
                INNER JOIN villes va      ON va.id = i.ville_arrivee_id
                WHERE v.statut = 'planifie'
                  AND c.statut = 'active'
                  AND v.date_heure_depart > UTC_TIMESTAMP()";

        $parametres = [];
        if ($depart !== '') {
            $sql .= ' AND vd.nom LIKE :depart';
            $parametres['depart'] = '%' . $depart . '%';
        }
        if ($arrivee !== '') {
            $sql .= ' AND va.nom LIKE :arrivee';
            $parametres['arrivee'] = '%' . $arrivee . '%';
        }
        if ($date !== '') {
            $sql .= ' AND DATE(v.date_heure_depart) = :date';
            $parametres['date'] = $date;
        }
        $sql .= ' ORDER BY v.date_heure_depart ASC';

        $stmt = $pdo->prepare($sql);
        $stmt->execute($parametres);

        return array_map([self::class, 'formaterPourAffichage'], $stmt->fetchAll());
    }

    /** @return array<string,mixed>|null */
    public static function trouverAvecDetails(int $id): ?array
    {
        $pdo = Database::connexion();
        $stmt = $pdo->prepare(
            "SELECT v.id, v.prix_fcfa, v.type_vehicule, v.date_heure_depart, v.statut,
                    c.nom AS compagnie_nom, c.statut AS compagnie_statut,
                    vd.nom AS ville_depart, va.nom AS ville_arrivee,
                    i.duree_estimee_minutes, i.gare_depart
             FROM voyages v
             INNER JOIN compagnies c  ON c.id = v.compagnie_id
             INNER JOIN itineraires i ON i.id = v.itineraire_id
             INNER JOIN villes vd     ON vd.id = i.ville_depart_id
             INNER JOIN villes va     ON va.id = i.ville_arrivee_id
             WHERE v.id = :id"
        );
        $stmt->execute(['id' => $id]);
        $ligne = $stmt->fetch();

        return $ligne !== false ? $ligne : null;
    }

    public static function estReservable(array $voyageLigne): bool
    {
        return $voyageLigne['statut'] === 'planifie'
            && $voyageLigne['compagnie_statut'] === 'active'
            && strtotime($voyageLigne['date_heure_depart']) > time();
    }

    /** @return int[] */
    public static function siegesOccupes(int $voyageId): array
    {
        $pdo = Database::connexion();
        $stmt = $pdo->prepare(
            "SELECT siege_numero FROM reservations
             WHERE voyage_id = :id AND statut IN ('en_attente_paiement','confirmee')"
        );
        $stmt->execute(['id' => $voyageId]);
        return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
    }

    /** @param array<string,mixed> $ligne */
    public static function formaterPourAffichage(array $ligne): array
    {
        $depart = new \DateTimeImmutable($ligne['date_heure_depart']);
        $arrivee = $depart->modify('+' . (int) $ligne['duree_estimee_minutes'] . ' minutes');

        return [
            'id'             => (int) $ligne['id'],
            'compagnie'       => $ligne['compagnie_nom'],
            'depart'          => $ligne['ville_depart'],
            'arrivee'         => $ligne['ville_arrivee'],
            'heureDepart'      => $depart->format('H:i'),
            'heureArrivee'      => $arrivee->format('H:i'),
            'duree'             => Aide::formaterDuree((int) $ligne['duree_estimee_minutes']),
            'type'              => self::LIBELLES_TYPE[$ligne['type_vehicule']] ?? 'Standard',
            'prix'              => (int) $ligne['prix_fcfa'],
            'gare'              => $ligne['gare_depart'] ?? '',
            'placesRestantes'    => isset($ligne['places_restantes']) ? max(0, (int) $ligne['places_restantes']) : null,
        ];
    }
}
