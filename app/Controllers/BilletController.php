<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;
use App\Core\Request;
use App\Core\Response;

final class BilletController
{
    private const LIBELLES_MOYEN = ['mtn' => 'MTN Mobile Money', 'moov' => 'Moov Money', 'celtiis' => 'Celtiis Cash'];

    public function index(Request $requete): Response
    {
        $codeCommande = trim((string) $requete->parametreRequete('commande', ''));
        $billet = null;

        if ($codeCommande !== '') {
            $pdo = Database::connexion();
            $stmt = $pdo->prepare(
                "SELECT co.code_commande, co.montant_total_fcfa, co.statut AS statut_commande,
                        p.moyen_paiement,
                        v.id AS voyage_id, v.date_heure_depart,
                        c.nom AS compagnie_nom,
                        vd.nom AS ville_depart, va.nom AS ville_arrivee,
                        i.duree_estimee_minutes, i.gare_depart
                 FROM commandes co
                 INNER JOIN voyages v      ON v.id = co.voyage_id
                 INNER JOIN compagnies c   ON c.id = v.compagnie_id
                 INNER JOIN itineraires i  ON i.id = v.itineraire_id
                 INNER JOIN villes vd      ON vd.id = i.ville_depart_id
                 INNER JOIN villes va      ON va.id = i.ville_arrivee_id
                 LEFT JOIN paiements p     ON p.commande_id = co.id AND p.statut = 'reussi'
                 WHERE co.code_commande = :code"
            );
            $stmt->execute(['code' => $codeCommande]);
            $ligne = $stmt->fetch();

            if ($ligne !== false && $ligne['statut_commande'] === 'payee') {
                $stmtSieges = $pdo->prepare(
                    'SELECT siege_numero, code_reservation, nom_passager FROM reservations
                     WHERE commande_id = (SELECT id FROM commandes WHERE code_commande = :code)
                     ORDER BY siege_numero ASC'
                );
                $stmtSieges->execute(['code' => $codeCommande]);
                $sieges = $stmtSieges->fetchAll();

                $depart = new \DateTimeImmutable($ligne['date_heure_depart']);
                $arrivee = $depart->modify('+' . (int) $ligne['duree_estimee_minutes'] . ' minutes');

                $billet = [
                    'code'          => $ligne['code_commande'],
                    'compagnie'      => $ligne['compagnie_nom'],
                    'depart'         => $ligne['ville_depart'],
                    'arrivee'        => $ligne['ville_arrivee'],
                    'date'            => $depart->format('Y-m-d'),
                    'heureDepart'     => $depart->format('H:i'),
                    'heureArrivee'     => $arrivee->format('H:i'),
                    'gare'             => $ligne['gare_depart'] ?? '',
                    'total'             => (int) $ligne['montant_total_fcfa'],
                    'methode'           => self::LIBELLES_MOYEN[$ligne['moyen_paiement'] ?? ''] ?? 'Mobile Money',
                    'seats'              => array_map(fn($s) => (int) $s['siege_numero'], $sieges),
                    'passagers'           => array_map(fn($s) => $s['nom_passager'], $sieges),
                    'codesReservation'     => array_map(fn($s) => $s['code_reservation'], $sieges),
                ];
            }
        }

        return Response::vue('voyageur/billet', ['billet' => $billet]);
    }
}
