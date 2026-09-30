<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use App\Core\ErreurMetier;

final class VoyageCompagnie
{
    /** @return array{voyageId:int, prixNetFcfa:int, prixFinalFcfa:int} */
    public static function creer(
        int $compagnieId,
        int $villeDepartId,
        int $villeArriveeId,
        int $dureeMinutes,
        ?string $gareDepart,
        string $date,
        string $heure,
        int $prixNetFcfa,
        string $typeVehicule
    ): array {
        $erreurs = [];

        if ($villeDepartId <= 0 || $villeArriveeId <= 0) {
            $erreurs[] = "Ville de départ et ville d'arrivée obligatoires.";
        } elseif ($villeDepartId === $villeArriveeId) {
            $erreurs[] = "La ville de départ et d'arrivée doivent être différentes.";
        }
        if ($dureeMinutes < 15 || $dureeMinutes > 1440) {
            $erreurs[] = 'Durée du trajet invalide (entre 15 minutes et 24h).';
        }
        if ($prixNetFcfa < 1) {
            $erreurs[] = 'Le montant que vous souhaitez recevoir par billet doit être supérieur à 0.';
        }
        if (!in_array($typeVehicule, ['standard', 'climatise', 'climatise_vip'], true)) {
            $typeVehicule = 'standard';
        }

        $dateHeureDepart = \DateTimeImmutable::createFromFormat('Y-m-d H:i', $date . ' ' . $heure, new \DateTimeZone('UTC'));
        if ($dateHeureDepart === false) {
            $erreurs[] = 'Date ou heure de départ invalide.';
        } elseif ($dateHeureDepart <= new \DateTimeImmutable('now', new \DateTimeZone('UTC'))) {
            $erreurs[] = 'La date de départ doit être dans le futur.';
        }

        if ($erreurs !== []) {
            throw new ErreurMetier('Données invalides.', 422, $erreurs);
        }

        // Le prix stocké en base est TOUJOURS le prix final payé par le
        // voyageur (frais inclus, jamais affichés séparément — décision
        // verrouillée). La compagnie saisit le montant NET qu'elle veut
        // toucher ; on ajoute les frais ici.
        $prixFinalFcfa = $prixNetFcfa + (int) config('metier.frais_service_fcfa');

        $pdo = Database::connexion();

        $stmtCompagnie = $pdo->prepare('SELECT statut FROM compagnies WHERE id = :cid');
        $stmtCompagnie->execute(['cid' => $compagnieId]);
        if ($stmtCompagnie->fetchColumn() !== 'active') {
            throw new ErreurMetier("Votre compagnie n'est pas active.", 403);
        }

        $stmtAbo = $pdo->prepare('SELECT statut FROM abonnements_compagnie WHERE compagnie_id = :cid');
        $stmtAbo->execute(['cid' => $compagnieId]);
        $statutAbonnement = $stmtAbo->fetchColumn();

        if (!in_array($statutAbonnement, ['essai', 'actif'], true)) {
            throw new ErreurMetier(
                'Votre abonnement ne permet pas de créer de nouveaux voyages actuellement (statut : ' . ($statutAbonnement ?: 'non configuré') . ').',
                403
            );
        }

        $stmtVilles = $pdo->prepare('SELECT id FROM villes WHERE id IN (:d, :a)');
        $stmtVilles->execute(['d' => $villeDepartId, 'a' => $villeArriveeId]);
        if (count($stmtVilles->fetchAll()) !== 2) {
            throw new ErreurMetier('Ville invalide.', 422);
        }

        $pdo->beginTransaction();
        try {
            $stmtItin = $pdo->prepare(
                'SELECT id FROM itineraires WHERE compagnie_id = :cid AND ville_depart_id = :d AND ville_arrivee_id = :a'
            );
            $stmtItin->execute(['cid' => $compagnieId, 'd' => $villeDepartId, 'a' => $villeArriveeId]);
            $itineraireId = $stmtItin->fetchColumn();

            if ($itineraireId === false) {
                $pdo->prepare(
                    'INSERT INTO itineraires (compagnie_id, ville_depart_id, ville_arrivee_id, duree_estimee_minutes, gare_depart)
                     VALUES (:cid, :d, :a, :duree, :gare)'
                )->execute([
                    'cid' => $compagnieId, 'd' => $villeDepartId, 'a' => $villeArriveeId,
                    'duree' => $dureeMinutes, 'gare' => $gareDepart,
                ]);
                $itineraireId = (int) $pdo->lastInsertId();
            }

            $pdo->prepare(
                'INSERT INTO voyages (compagnie_id, itineraire_id, date_heure_depart, prix_fcfa, type_vehicule, statut)
                 VALUES (:cid, :iid, :date, :prix, :type, "planifie")'
            )->execute([
                'cid'  => $compagnieId, 'iid' => $itineraireId,
                'date'  => $dateHeureDepart->format('Y-m-d H:i:s'),
                'prix'   => $prixFinalFcfa, 'type' => $typeVehicule,
            ]);
            $voyageId = (int) $pdo->lastInsertId();

            $pdo->commit();

            return ['voyageId' => $voyageId, 'prixNetFcfa' => $prixNetFcfa, 'prixFinalFcfa' => $prixFinalFcfa];
        } catch (\Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }
}
