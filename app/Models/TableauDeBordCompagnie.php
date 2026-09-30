<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class TableauDeBordCompagnie
{
    public static function donnees(int $compagnieId, int $voyageSelectionneIdDemande = 0): array
    {
        $pdo = Database::connexion();
        $frais = (int) config('metier.frais_service_fcfa');

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM voyages WHERE compagnie_id = :cid AND statut = 'planifie'");
        $stmt->execute(['cid' => $compagnieId]);
        $voyagesActifs = (int) $stmt->fetchColumn();

        $stmt = $pdo->prepare(
            "SELECT COUNT(*) FROM reservations r
             INNER JOIN voyages v ON v.id = r.voyage_id
             WHERE v.compagnie_id = :cid AND r.statut = 'confirmee' AND DATE(r.created_at) = CURDATE()"
        );
        $stmt->execute(['cid' => $compagnieId]);
        $reservationsAujourdhui = (int) $stmt->fetchColumn();

        $stmt = $pdo->prepare(
            "SELECT COALESCE(SUM(r.prix_paye_fcfa - :frais), 0) FROM reservations r
             INNER JOIN voyages v ON v.id = r.voyage_id
             WHERE v.compagnie_id = :cid AND r.statut = 'confirmee'
               AND MONTH(r.created_at) = MONTH(UTC_TIMESTAMP()) AND YEAR(r.created_at) = YEAR(UTC_TIMESTAMP())"
        );
        $stmt->execute(['frais' => $frais, 'cid' => $compagnieId]);
        $revenuMoisCompagnie = (int) $stmt->fetchColumn();

        $stmt = $pdo->prepare(
            "SELECT v.id, v.places_totales,
                    (SELECT COUNT(*) FROM reservations r WHERE r.voyage_id = v.id AND r.statut IN ('en_attente_paiement','confirmee')) AS occupes
             FROM voyages v WHERE v.compagnie_id = :cid AND v.statut = 'planifie'"
        );
        $stmt->execute(['cid' => $compagnieId]);
        $voyagesPourTaux = $stmt->fetchAll();
        $tauxRemplissage = 0;
        if ($voyagesPourTaux !== []) {
            $totalPlaces = array_sum(array_column($voyagesPourTaux, 'places_totales'));
            $totalOccupes = array_sum(array_column($voyagesPourTaux, 'occupes'));
            $tauxRemplissage = $totalPlaces > 0 ? round(($totalOccupes / $totalPlaces) * 100) : 0;
        }

        $stmt = $pdo->prepare(
            "SELECT v.id, v.date_heure_depart, v.prix_fcfa, v.statut,
                    vd.nom AS ville_depart, va.nom AS ville_arrivee,
                    v.places_totales - (
                        SELECT COUNT(*) FROM reservations r WHERE r.voyage_id = v.id AND r.statut IN ('en_attente_paiement','confirmee')
                    ) AS places_restantes
             FROM voyages v
             INNER JOIN itineraires i ON i.id = v.itineraire_id
             INNER JOIN villes vd     ON vd.id = i.ville_depart_id
             INNER JOIN villes va     ON va.id = i.ville_arrivee_id
             WHERE v.compagnie_id = :cid
             ORDER BY v.date_heure_depart DESC LIMIT 30"
        );
        $stmt->execute(['cid' => $compagnieId]);
        $voyagesListe = $stmt->fetchAll();

        $stmt = $pdo->prepare('SELECT statut, montant_mensuel_fcfa, date_fin_periode FROM abonnements_compagnie WHERE compagnie_id = :cid');
        $stmt->execute(['cid' => $compagnieId]);
        $abonnementLigne = $stmt->fetch();
        $abonnement = $abonnementLigne !== false ? $abonnementLigne : null;

        $listeVilles = $pdo->query('SELECT id, nom FROM villes ORDER BY nom ASC')->fetchAll();

        $stmt = $pdo->prepare(
            "SELECT r.code_reservation, r.nom_passager, r.telephone_passager, r.siege_numero, r.statut,
                    vd.nom AS ville_depart, va.nom AS ville_arrivee, r.created_at
             FROM reservations r
             INNER JOIN voyages v      ON v.id = r.voyage_id
             INNER JOIN itineraires i  ON i.id = v.itineraire_id
             INNER JOIN villes vd      ON vd.id = i.ville_depart_id
             INNER JOIN villes va      ON va.id = i.ville_arrivee_id
             WHERE v.compagnie_id = :cid ORDER BY r.created_at DESC LIMIT 50"
        );
        $stmt->execute(['cid' => $compagnieId]);
        $reservationsListe = $stmt->fetchAll();

        // --- Onglet Passagers : voyage sélectionné + plan des sièges ------
        $stmt = $pdo->prepare(
            "SELECT v.id, v.date_heure_depart, vd.nom AS ville_depart, va.nom AS ville_arrivee
             FROM voyages v
             INNER JOIN itineraires i ON i.id = v.itineraire_id
             INNER JOIN villes vd     ON vd.id = i.ville_depart_id
             INNER JOIN villes va     ON va.id = i.ville_arrivee_id
             WHERE v.compagnie_id = :cid ORDER BY v.date_heure_depart DESC"
        );
        $stmt->execute(['cid' => $compagnieId]);
        $voyagesPourSelect = $stmt->fetchAll();

        $voyageSelectionneId = $voyageSelectionneIdDemande > 0 ? $voyageSelectionneIdDemande : (int) ($voyagesPourSelect[0]['id'] ?? 0);

        $passagersParSiege = [];
        if ($voyageSelectionneId > 0) {
            $stmt = $pdo->prepare(
                "SELECT siege_numero, nom_passager, telephone_passager, statut FROM reservations
                 WHERE voyage_id = :vid AND statut IN ('en_attente_paiement','confirmee')"
            );
            $stmt->execute(['vid' => $voyageSelectionneId]);
            foreach ($stmt->fetchAll() as $ligne) {
                $passagersParSiege[(int) $ligne['siege_numero']] = $ligne;
            }
        }

        // --- Statistiques 30 derniers jours ---------------------------
        $stmt = $pdo->prepare(
            "SELECT COUNT(*) FROM reservations r INNER JOIN voyages v ON v.id = r.voyage_id
             WHERE v.compagnie_id = :cid AND r.statut = 'confirmee' AND r.created_at >= UTC_TIMESTAMP() - INTERVAL 30 DAY"
        );
        $stmt->execute(['cid' => $compagnieId]);
        $billetsVendus30j = (int) $stmt->fetchColumn();

        $stmt = $pdo->prepare(
            "SELECT COALESCE(SUM(r.prix_paye_fcfa - :frais), 0) FROM reservations r INNER JOIN voyages v ON v.id = r.voyage_id
             WHERE v.compagnie_id = :cid AND r.statut = 'confirmee' AND r.created_at >= UTC_TIMESTAMP() - INTERVAL 30 DAY"
        );
        $stmt->execute(['frais' => $frais, 'cid' => $compagnieId]);
        $revenuNet30j = (int) $stmt->fetchColumn();

        $stmt = $pdo->prepare(
            "SELECT SUM(CASE WHEN r.statut = 'annulee' THEN 1 ELSE 0 END) AS annulees,
                    SUM(CASE WHEN r.statut IN ('confirmee','annulee') THEN 1 ELSE 0 END) AS decidees
             FROM reservations r INNER JOIN voyages v ON v.id = r.voyage_id
             WHERE v.compagnie_id = :cid AND r.created_at >= UTC_TIMESTAMP() - INTERVAL 30 DAY"
        );
        $stmt->execute(['cid' => $compagnieId]);
        $ligneAnnulation = $stmt->fetch();
        $tauxAnnulation30j = ((int) $ligneAnnulation['decidees'] > 0)
            ? round(((int) $ligneAnnulation['annulees'] / (int) $ligneAnnulation['decidees']) * 100, 1)
            : 0.0;

        $stmt = $pdo->prepare(
            "SELECT DATE(r.created_at) AS jour, COUNT(*) AS nb
             FROM reservations r INNER JOIN voyages v ON v.id = r.voyage_id
             WHERE v.compagnie_id = :cid AND r.statut = 'confirmee' AND r.created_at >= UTC_TIMESTAMP() - INTERVAL 7 DAY
             GROUP BY DATE(r.created_at)"
        );
        $stmt->execute(['cid' => $compagnieId]);
        $ventesParJourBrut = array_column($stmt->fetchAll(), 'nb', 'jour');

        $ventes7Jours = [];
        for ($i = 6; $i >= 0; $i--) {
            $jour = (new \DateTimeImmutable('now', new \DateTimeZone('UTC')))->modify("-{$i} days");
            $cle = $jour->format('Y-m-d');
            $ventes7Jours[] = [
                'label' => ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'][(int) $jour->format('N') - 1],
                'nb'     => (int) ($ventesParJourBrut[$cle] ?? 0),
            ];
        }
        $maxVentesJour = max(1, max(array_column($ventes7Jours, 'nb')));

        $stmt = $pdo->prepare(
            "SELECT vd.nom AS depart, va.nom AS arrivee, COUNT(DISTINCT v.id) AS nb_voyages, COUNT(r.id) AS billets_vendus
             FROM voyages v
             INNER JOIN itineraires i ON i.id = v.itineraire_id
             INNER JOIN villes vd     ON vd.id = i.ville_depart_id
             INNER JOIN villes va     ON va.id = i.ville_arrivee_id
             LEFT JOIN reservations r ON r.voyage_id = v.id AND r.statut = 'confirmee'
             WHERE v.compagnie_id = :cid GROUP BY vd.nom, va.nom ORDER BY billets_vendus DESC LIMIT 5"
        );
        $stmt->execute(['cid' => $compagnieId]);
        $trajetsPopulaires = $stmt->fetchAll();

        // --- Notifications dérivées à la volée -------------------------
        $notifications = [];
        $stmt = $pdo->prepare(
            "SELECT vd.nom AS depart, va.nom AS arrivee, COUNT(r.id) AS nb, MAX(r.created_at) AS derniere
             FROM reservations r
             INNER JOIN voyages v      ON v.id = r.voyage_id
             INNER JOIN itineraires i  ON i.id = v.itineraire_id
             INNER JOIN villes vd      ON vd.id = i.ville_depart_id
             INNER JOIN villes va      ON va.id = i.ville_arrivee_id
             WHERE v.compagnie_id = :cid AND r.statut = 'confirmee' AND r.created_at >= UTC_TIMESTAMP() - INTERVAL 24 HOUR
             GROUP BY v.id ORDER BY derniere DESC LIMIT 5"
        );
        $stmt->execute(['cid' => $compagnieId]);
        foreach ($stmt->fetchAll() as $n) {
            $notifications[] = [
                'type'  => 'success',
                'texte'  => (int) $n['nb'] . ' nouvelle' . ((int) $n['nb'] > 1 ? 's' : '') . ' réservation' . ((int) $n['nb'] > 1 ? 's' : '') . ' sur ' . $n['depart'] . ' → ' . $n['arrivee'],
            ];
        }

        if ($abonnement) {
            if ($abonnement['statut'] === 'grace') {
                $notifications[] = ['type' => 'error', 'texte' => 'Votre abonnement est en délai de grâce — réglez-le pour éviter le passage en lecture seule.'];
            } elseif (in_array($abonnement['statut'], ['essai', 'actif'], true) && $abonnement['date_fin_periode']) {
                $joursRestants = (int) floor((strtotime($abonnement['date_fin_periode']) - time()) / 86400);
                if ($joursRestants <= 7 && $joursRestants >= 0) {
                    $notifications[] = ['type' => 'warn', 'texte' => 'Votre abonnement expire dans ' . $joursRestants . ' jour' . ($joursRestants > 1 ? 's' : '') . '.'];
                }
            }
        }

        foreach ($voyagesListe as $v) {
            if ($v['statut'] === 'planifie' && (int) $v['places_restantes'] <= 0) {
                $notifications[] = ['type' => 'warn', 'texte' => 'Votre voyage ' . $v['ville_depart'] . ' → ' . $v['ville_arrivee'] . ' est complet.'];
            }
        }

        return compact(
            'voyagesActifs', 'reservationsAujourdhui', 'revenuMoisCompagnie', 'tauxRemplissage',
            'voyagesListe', 'abonnement', 'listeVilles', 'reservationsListe',
            'voyagesPourSelect', 'voyageSelectionneId', 'passagersParSiege',
            'billetsVendus30j', 'revenuNet30j', 'tauxAnnulation30j', 'ventes7Jours', 'maxVentesJour',
            'trajetsPopulaires', 'notifications'
        );
    }
}
