<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Aide;
use App\Core\Database;
use App\Core\ErreurMetier;
use App\Core\Mailer;

final class Compagnie
{
    public static function trouverParEmail(string $email): ?array
    {
        $pdo = Database::connexion();
        $stmt = $pdo->prepare('SELECT id, nom, mot_de_passe, statut FROM compagnies WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $ligne = $stmt->fetch();
        return $ligne !== false ? $ligne : null;
    }

    public static function estActive(int $id): bool
    {
        $pdo = Database::connexion();
        $stmt = $pdo->prepare("SELECT statut FROM compagnies WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetchColumn() === 'active';
    }

    public static function trouver(int $id): ?array
    {
        $pdo = Database::connexion();
        $stmt = $pdo->prepare('SELECT * FROM compagnies WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $ligne = $stmt->fetch();
        return $ligne !== false ? $ligne : null;
    }

    /** @return array<int, array<string,mixed>> */
    public static function listerToutes(): array
    {
        $pdo = Database::connexion();
        return $pdo->query(
            "SELECT c.id, c.nom, c.statut, c.created_at,
                    a.statut AS statut_abonnement, a.date_fin_periode,
                    (SELECT COUNT(*) FROM voyages v WHERE v.compagnie_id = c.id AND v.statut = 'planifie') AS voyages_actifs
             FROM compagnies c
             LEFT JOIN abonnements_compagnie a ON a.compagnie_id = c.id
             ORDER BY c.created_at DESC"
        )->fetchAll();
    }

    public static function creerDemande(
        string $nom,
        string $email,
        string $telephone,
        string $ville,
        int $nombreVehicules,
        ?string $message,
        ?string $cheminDocument
    ): void {
        $pdo = Database::connexion();

        $stmtExiste = $pdo->prepare('SELECT id FROM compagnies WHERE email = :email');
        $stmtExiste->execute(['email' => $email]);
        if ($stmtExiste->fetch() !== false) {
            throw new ErreurMetier('Une compagnie avec cet email existe déjà. Essayez de vous connecter.', 422);
        }

        $pdo->prepare(
            'INSERT INTO compagnies
                (nom, email, telephone, mot_de_passe, ville_siege, nombre_vehicules,
                 message_presentation, document_justificatif_chemin, statut)
             VALUES (:nom, :email, :tel, :mdp, :ville, :nb, :message, :document, "en_attente_validation")'
        )->execute([
            'nom'   => $nom,
            'email'  => $email,
            'tel'    => $telephone,
            // Mot de passe temporaire inutilisable — remplacé par un vrai
            // mot de passe (envoyé par email) lors de la validation admin.
            'mdp'    => password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT),
            'ville'  => $ville,
            'nb'      => $nombreVehicules,
            'message'  => $message,
            'document'  => $cheminDocument,
        ]);
    }

    public static function valider(int $compagnieId, int $adminId): array
    {
        $pdo = Database::connexion();
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare('SELECT id, nom, email FROM compagnies WHERE id = :id FOR UPDATE');
            $stmt->execute(['id' => $compagnieId]);
            $compagnie = $stmt->fetch();

            if ($compagnie === false) {
                throw new ErreurMetier('Compagnie introuvable.', 404);
            }
            $stmtStatut = $pdo->prepare('SELECT statut FROM compagnies WHERE id = :id');
            $stmtStatut->execute(['id' => $compagnieId]);
            if ($stmtStatut->fetchColumn() !== 'en_attente_validation') {
                throw new ErreurMetier('Cette compagnie ne peut plus être validée dans son état actuel.', 409);
            }

            $motDePasseTemporaire = Mailer::genererMotDePasseTemporaire();

            $pdo->prepare("UPDATE compagnies SET statut = 'active', mot_de_passe = :mdp WHERE id = :id")
                ->execute(['mdp' => password_hash($motDePasseTemporaire, PASSWORD_DEFAULT), 'id' => $compagnieId]);

            $dateDebut = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
            $dateFin = $dateDebut->modify('+' . config('metier.essai_gratuit_mois') . ' month');

            $pdo->prepare(
                'INSERT INTO abonnements_compagnie
                    (compagnie_id, statut, date_debut_periode, date_fin_periode, validee_par_admin_id)
                 VALUES (:cid, "essai", :debut, :fin, :admin_id)
                 ON DUPLICATE KEY UPDATE
                    statut = "essai", date_debut_periode = :debut2, date_fin_periode = :fin2,
                    validee_par_admin_id = :admin_id2, suspendu_depuis = NULL'
            )->execute([
                'cid'    => $compagnieId, 'debut' => $dateDebut->format('Y-m-d'), 'fin' => $dateFin->format('Y-m-d'),
                'admin_id' => $adminId, 'debut2' => $dateDebut->format('Y-m-d'), 'fin2' => $dateFin->format('Y-m-d'),
                'admin_id2' => $adminId,
            ]);

            $pdo->commit();

            // Email envoyé APRÈS le commit : son échec ne doit jamais
            // annuler la validation déjà acquise en base.
            $lienConnexion = Aide::urlBase() . '/compagnie/connexion';
            $corpsEmail = '
                <div style="font-family:Arial,sans-serif; max-width:520px; margin:0 auto; color:#111;">
                  <h2 style="color:#0a0a0f;">Bienvenue sur e-réservation, ' . htmlspecialchars($compagnie['nom'], ENT_QUOTES, 'UTF-8') . ' !</h2>
                  <p>Votre demande de partenariat a été validée. Vous bénéficiez d\'un essai gratuit de ' . config('metier.essai_gratuit_mois') . ' mois.</p>
                  <p>Voici vos identifiants de connexion à l\'espace compagnie :</p>
                  <table style="background:#f5f5f5; border-radius:8px; padding:16px; width:100%; border-collapse:collapse;">
                    <tr><td style="padding:6px 0; color:#555;">Email</td><td style="padding:6px 0; font-weight:bold;">' . htmlspecialchars($compagnie['email'], ENT_QUOTES, 'UTF-8') . '</td></tr>
                    <tr><td style="padding:6px 0; color:#555;">Mot de passe</td><td style="padding:6px 0; font-weight:bold; font-family:monospace; font-size:16px;">' . htmlspecialchars($motDePasseTemporaire, ENT_QUOTES, 'UTF-8') . '</td></tr>
                  </table>
                  <p style="margin-top:20px;">
                    <a href="' . htmlspecialchars($lienConnexion, ENT_QUOTES, 'UTF-8') . '" style="background:#f0b429; color:#0a0a0f; padding:12px 24px; border-radius:6px; text-decoration:none; font-weight:bold; display:inline-block;">Se connecter à mon espace</a>
                  </p>
                  <p style="font-size:13px; color:#777; margin-top:24px;">Par sécurité, nous vous recommandons de changer ce mot de passe dès votre première connexion.</p>
                </div>
            ';
            $resultatEmail = Mailer::envoyer($compagnie['email'], 'Votre compte e-réservation est activé', $corpsEmail);
            if (!($resultatEmail['succes'] ?? false) && !config('app.debug')) {
                // En production, ne pas laisser un compte actif dont les
                // identifiants n'ont pas pu être transmis.
                $pdo->beginTransaction();
                try {
                    $pdo->prepare("UPDATE compagnies SET statut = 'en_attente_validation' WHERE id = :id")
                        ->execute(['id' => $compagnieId]);
                    $pdo->prepare('DELETE FROM abonnements_compagnie WHERE compagnie_id = :id')
                        ->execute(['id' => $compagnieId]);
                    $pdo->commit();
                } catch (\Throwable $e) {
                    if ($pdo->inTransaction()) $pdo->rollBack();
                    throw $e;
                }
                throw new ErreurMetier("La compagnie n'a pas été activée car l'email d'activation n'a pas pu être envoyé.", 503);
            }
            return [
                'emailEnvoye' => (bool) ($resultatEmail['succes'] ?? false),
                'motDePasseTemporaire' => ($resultatEmail['succes'] ?? false) ? null : (config('app.debug') ? $motDePasseTemporaire : null),
            ];
        } catch (\Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    public static function rejeter(int $compagnieId): void
    {
        $pdo = Database::connexion();
        $stmt = $pdo->prepare("UPDATE compagnies SET statut = 'rejetee' WHERE id = :id AND statut = 'en_attente_validation'");
        $stmt->execute(['id' => $compagnieId]);
        if ($stmt->rowCount() === 0) {
            throw new ErreurMetier('Compagnie introuvable.', 404);
        }
    }

    public static function desactiver(int $compagnieId): void
    {
        $pdo = Database::connexion();
        $stmt = $pdo->prepare("UPDATE compagnies SET statut = 'desactivee' WHERE id = :id AND statut = 'active'");
        $stmt->execute(['id' => $compagnieId]);
        if ($stmt->rowCount() === 0) {
            throw new ErreurMetier('Compagnie introuvable ou déjà non active.', 404);
        }
    }

    public static function reactiver(int $compagnieId): void
    {
        $pdo = Database::connexion();
        $stmt = $pdo->prepare("UPDATE compagnies SET statut = 'active' WHERE id = :id AND statut = 'desactivee'");
        $stmt->execute(['id' => $compagnieId]);
        if ($stmt->rowCount() === 0) {
            throw new ErreurMetier('Compagnie introuvable ou pas dans un état désactivé.', 404);
        }
    }

    public static function supprimer(int $compagnieId): void
    {
        $pdo = Database::connexion();
        $stmt = $pdo->prepare('SELECT statut FROM compagnies WHERE id = :id');
        $stmt->execute(['id' => $compagnieId]);
        $compagnie = $stmt->fetch();

        if ($compagnie === false) {
            throw new ErreurMetier('Compagnie introuvable.', 404);
        }
        if (!in_array($compagnie['statut'], ['en_attente_validation', 'rejetee'], true)) {
            throw new ErreurMetier(
                'Impossible de supprimer une compagnie déjà active ou désactivée (historique à préserver). Utilisez "Désactiver" à la place.',
                409
            );
        }
        $pdo->prepare('DELETE FROM compagnies WHERE id = :id')->execute(['id' => $compagnieId]);
    }

    public static function changerMotDePasse(int $compagnieId, string $motDePasseActuel, string $nouveauMotDePasse): void
    {
        $pdo = Database::connexion();
        $stmt = $pdo->prepare('SELECT mot_de_passe FROM compagnies WHERE id = :id');
        $stmt->execute(['id' => $compagnieId]);
        $hash = $stmt->fetchColumn();

        if ($hash === false || !password_verify($motDePasseActuel, $hash)) {
            throw new ErreurMetier('Mot de passe actuel incorrect.', 403);
        }

        $pdo->prepare('UPDATE compagnies SET mot_de_passe = :mdp WHERE id = :id')
            ->execute(['mdp' => password_hash($nouveauMotDePasse, PASSWORD_DEFAULT), 'id' => $compagnieId]);
    }
}
