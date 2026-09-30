-- =====================================================================
-- e-réservation — Schéma de base de données v2 (MySQL 8+ / MariaDB 10.2+)
-- Modèle économique verrouillé :
--   - Frais de service : 250 FCFA fixe/billet, inclus dans prix_fcfa,
--     jamais stocké séparément, jamais remboursé.
--   - Abonnement compagnie : 20 000 FCFA/mois fixe, essai 1 mois
--     activé uniquement après validation manuelle du super-admin.
--
-- Changements v1 -> v2 :
--   - Nouvelle table `commandes` : regroupe plusieurs sièges réservés
--     ensemble sous UN SEUL paiement (achat multi-places).
--   - `reservations.commande_id` : chaque siège reste une ligne
--     indépendante (unicité de siège inchangée) mais rattachée à sa
--     commande.
--   - `paiements` référence désormais `commande_id` (un paiement par
--     commande, pas par siège individuel).
--   - `voyages.type_vehicule` et `itineraires.gare_depart` ajoutés
--     pour un affichage réaliste côté recherche/résultats.
--
-- Idempotent : peut être ré-exécuté sans erreur (DROP IF EXISTS).
-- =====================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS ereservation
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ereservation;

DROP TABLE IF EXISTS remboursements;
DROP TABLE IF EXISTS paiements;
DROP TABLE IF EXISTS reservations;
DROP TABLE IF EXISTS commandes;
DROP TABLE IF EXISTS voyages;
DROP TABLE IF EXISTS itineraires;
DROP TABLE IF EXISTS abonnements_compagnie;
DROP TABLE IF EXISTS compagnies;
DROP TABLE IF EXISTS administrateurs;
DROP TABLE IF EXISTS villes;

SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================================
-- 1. villes
-- =====================================================================
CREATE TABLE villes (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom           VARCHAR(100) NOT NULL,
    pays          VARCHAR(100) NOT NULL DEFAULT 'Bénin',
    created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_villes_nom_pays (nom, pays)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================================
-- 2. administrateurs
-- =====================================================================
CREATE TABLE administrateurs (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom             VARCHAR(150) NOT NULL,
    email           VARCHAR(190) NOT NULL,
    mot_de_passe    VARCHAR(255) NOT NULL,
    actif           TINYINT(1) NOT NULL DEFAULT 1,
    created_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_administrateurs_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================================
-- 3. compagnies
-- =====================================================================
CREATE TABLE compagnies (
    id                          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom                         VARCHAR(150) NOT NULL,
    email                       VARCHAR(190) NOT NULL,
    telephone                   VARCHAR(30)  NOT NULL,
    mot_de_passe                VARCHAR(255) NOT NULL,
    ville_siege                 VARCHAR(150) NULL,
    nombre_vehicules            SMALLINT UNSIGNED NULL,
    message_presentation        TEXT NULL,
    document_justificatif_chemin VARCHAR(255) NULL,
    numero_mobile_money_versement VARCHAR(30) NULL,
    fedapay_sous_compte_id      VARCHAR(100) NULL,
    statut                      ENUM('en_attente_validation','active','rejetee','desactivee')
                                 NOT NULL DEFAULT 'en_attente_validation',
    created_at                  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at                  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_compagnies_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================================
-- 4. abonnements_compagnie
-- =====================================================================
CREATE TABLE abonnements_compagnie (
    id                          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    compagnie_id                INT UNSIGNED NOT NULL,
    statut                      ENUM(
                                    'en_attente_validation',
                                    'essai',
                                    'actif',
                                    'grace',
                                    'lecture_seule'
                                 ) NOT NULL DEFAULT 'en_attente_validation',
    montant_mensuel_fcfa        INT UNSIGNED NOT NULL DEFAULT 20000,
    date_debut_periode          DATE NULL,
    date_fin_periode            DATE NULL,
    date_derniere_relance_j7    DATE NULL,
    date_derniere_relance_j1    DATE NULL,
    suspendu_depuis             DATE NULL,
    validee_par_admin_id        INT UNSIGNED NULL,
    created_at                  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at                  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_abonnement_compagnie (compagnie_id),
    CONSTRAINT fk_abonnement_compagnie FOREIGN KEY (compagnie_id)
        REFERENCES compagnies(id) ON DELETE CASCADE,
    CONSTRAINT fk_abonnement_valide_par FOREIGN KEY (validee_par_admin_id)
        REFERENCES administrateurs(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================================
-- 5. itineraires
-- =====================================================================
CREATE TABLE itineraires (
    id                    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    compagnie_id          INT UNSIGNED NOT NULL,
    ville_depart_id       INT UNSIGNED NOT NULL,
    ville_arrivee_id      INT UNSIGNED NOT NULL,
    duree_estimee_minutes SMALLINT UNSIGNED NOT NULL,
    gare_depart           VARCHAR(150) NULL,
    actif                 TINYINT(1) NOT NULL DEFAULT 1,
    created_at            TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_itineraire_compagnie FOREIGN KEY (compagnie_id)
        REFERENCES compagnies(id) ON DELETE CASCADE,
    CONSTRAINT fk_itineraire_ville_depart FOREIGN KEY (ville_depart_id)
        REFERENCES villes(id),
    CONSTRAINT fk_itineraire_ville_arrivee FOREIGN KEY (ville_arrivee_id)
        REFERENCES villes(id),
    CONSTRAINT chk_itineraire_villes_differentes
        CHECK (ville_depart_id <> ville_arrivee_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================================
-- 6. voyages
-- =====================================================================
CREATE TABLE voyages (
    id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    compagnie_id      INT UNSIGNED NOT NULL,
    itineraire_id     INT UNSIGNED NOT NULL,
    date_heure_depart DATETIME NOT NULL,
    prix_fcfa         INT UNSIGNED NOT NULL,
    places_totales    TINYINT UNSIGNED NOT NULL DEFAULT 50,
    type_vehicule     ENUM('standard','climatise','climatise_vip') NOT NULL DEFAULT 'standard',
    statut            ENUM('planifie','annule','termine') NOT NULL DEFAULT 'planifie',
    created_at        TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at        TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_voyage_compagnie FOREIGN KEY (compagnie_id)
        REFERENCES compagnies(id) ON DELETE CASCADE,
    CONSTRAINT fk_voyage_itineraire FOREIGN KEY (itineraire_id)
        REFERENCES itineraires(id),
    CONSTRAINT chk_voyage_places_max CHECK (places_totales = 50),
    INDEX idx_voyages_depart (date_heure_depart),
    INDEX idx_voyages_itineraire_depart (itineraire_id, date_heure_depart)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================================
-- 7. commandes
-- =====================================================================
CREATE TABLE commandes (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code_commande        VARCHAR(20) NOT NULL,
    voyage_id            INT UNSIGNED NOT NULL,
    nombre_sieges        TINYINT UNSIGNED NOT NULL,
    montant_total_fcfa   INT UNSIGNED NOT NULL,
    telephone_contact    VARCHAR(30) NOT NULL,
    email_contact        VARCHAR(190) NULL,
    fedapay_transaction_id VARCHAR(50) NULL,
    statut                ENUM('en_attente_paiement','payee','expiree','annulee')
                          NOT NULL DEFAULT 'en_attente_paiement',
    expire_a              DATETIME NOT NULL,
    created_at            TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at            TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_commandes_code (code_commande),
    UNIQUE KEY uq_commandes_fedapay_tx (fedapay_transaction_id),
    CONSTRAINT fk_commande_voyage FOREIGN KEY (voyage_id)
        REFERENCES voyages(id),
    CONSTRAINT chk_commande_nombre_sieges CHECK (nombre_sieges BETWEEN 1 AND 4),
    INDEX idx_commandes_statut_expiration (statut, expire_a)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================================
-- 8. reservations
-- =====================================================================
CREATE TABLE reservations (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    commande_id         INT UNSIGNED NOT NULL,
    voyage_id           INT UNSIGNED NOT NULL,
    siege_numero        TINYINT UNSIGNED NOT NULL,
    statut              ENUM('en_attente_paiement','confirmee','expiree','annulee')
                         NOT NULL DEFAULT 'en_attente_paiement',
    siege_actif TINYINT UNSIGNED
        GENERATED ALWAYS AS (
            CASE WHEN statut IN ('en_attente_paiement','confirmee')
                 THEN siege_numero ELSE NULL END
        ) STORED,
    code_reservation    VARCHAR(20) NOT NULL,
    nom_passager        VARCHAR(150) NOT NULL,
    telephone_passager  VARCHAR(30)  NOT NULL,
    email_passager      VARCHAR(190) NULL,
    prix_paye_fcfa      INT UNSIGNED NOT NULL,
    expire_a            DATETIME NOT NULL,
    created_at          TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_reservation_commande FOREIGN KEY (commande_id)
        REFERENCES commandes(id) ON DELETE CASCADE,
    CONSTRAINT fk_reservation_voyage FOREIGN KEY (voyage_id)
        REFERENCES voyages(id) ON DELETE CASCADE,
    CONSTRAINT chk_reservation_siege_numero CHECK (siege_numero BETWEEN 1 AND 50),
    UNIQUE KEY uq_reservations_code (code_reservation),
    UNIQUE KEY uq_reservations_siege_actif (voyage_id, siege_actif),
    INDEX idx_reservations_statut_expiration (statut, expire_a),
    INDEX idx_reservations_commande (commande_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================================
-- 9. paiements
-- =====================================================================
CREATE TABLE paiements (
    id                      INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    commande_id             INT UNSIGNED NOT NULL,
    fournisseur             ENUM('fedapay') NOT NULL DEFAULT 'fedapay',
    moyen_paiement          ENUM('mtn','moov','celtiis') NULL,
    reference_transaction   VARCHAR(150) NOT NULL,
    montant_fcfa            INT UNSIGNED NOT NULL,
    statut                  ENUM('en_attente','reussi','echoue') NOT NULL DEFAULT 'en_attente',
    donnees_brutes          JSON NULL,
    created_at              TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at              TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_paiement_commande FOREIGN KEY (commande_id)
        REFERENCES commandes(id) ON DELETE CASCADE,
    UNIQUE KEY uq_paiements_idempotence (fournisseur, reference_transaction)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================================
-- 10. remboursements
-- =====================================================================
CREATE TABLE remboursements (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    reservation_id  INT UNSIGNED NOT NULL,
    statut          ENUM('a_traiter','traite','refuse') NOT NULL DEFAULT 'a_traiter',
    montant_fcfa    INT UNSIGNED NOT NULL,
    notes           TEXT NULL,
    created_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_remboursement_reservation FOREIGN KEY (reservation_id)
        REFERENCES reservations(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================================
-- SEEDS
-- =====================================================================

INSERT INTO villes (nom, pays) VALUES
    ('Cotonou', 'Bénin'), ('Porto-Novo', 'Bénin'), ('Parakou', 'Bénin'),
    ('Abomey-Calavi', 'Bénin'), ('Abomey', 'Bénin'), ('Bohicon', 'Bénin'),
    ('Natitingou', 'Bénin'), ('Djougou', 'Bénin'), ('Lokossa', 'Bénin'),
    ('Ouidah', 'Bénin'), ('Kandi', 'Bénin'), ('Savalou', 'Bénin')
ON DUPLICATE KEY UPDATE nom = VALUES(nom);

-- Super-admin par défaut — mot de passe à changer en production.
-- Hash = password_hash('ChangeMoi123!', PASSWORD_DEFAULT) — vérifié réellement.
INSERT INTO administrateurs (nom, email, mot_de_passe, actif) VALUES
    ('Super Administrateur', 'admin@e-reservation.bj',
     '$2y$10$noZXQTs.7I8RXu.DIGtg/ev2wpNYuaPgFleXp6Z8Gctn9VZoI12XO', 1)
ON DUPLICATE KEY UPDATE nom = VALUES(nom);
