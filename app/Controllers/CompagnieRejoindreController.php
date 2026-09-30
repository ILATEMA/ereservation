<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\ErreurMetier;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Models\Compagnie;

final class CompagnieRejoindreController
{
    public function formulaire(Request $requete): Response
    {
        return Response::vue('voyageur/rejoindre', [
            'erreurs'    => [],
            'succes'      => false,
            'jetonCsrf'    => Session::genererJetonCsrf(),
        ]);
    }

    public function traiter(Request $requete): Response
    {
        $erreurs = [];

        if (!Session::verifierJetonCsrf((string) $requete->entree('csrf'))) {
            $erreurs[] = 'Session expirée, merci de réessayer.';
        }

        $nom              = trim((string) $requete->entree('nom_compagnie', ''));
        $ville             = trim((string) $requete->entree('ville', ''));
        $nombreVehicules     = (int) $requete->entree('vehicules', 0);
        $telephone            = \App\Core\Aide::nettoyerTelephone((string) $requete->entree('telephone', ''));
        $email                 = trim((string) $requete->entree('email', ''));
        $message                = trim((string) $requete->entree('message', '')) ?: null;

        if ($nom === '') {
            $erreurs[] = 'Le nom de la compagnie est obligatoire.';
        }
        if ($ville === '') {
            $erreurs[] = 'La ville est obligatoire.';
        }
        if ($nombreVehicules < 1) {
            $erreurs[] = 'Le nombre de véhicules doit être au moins 1.';
        }
        if (!preg_match('/^\+?[0-9]{8,15}$/', $telephone)) {
            $erreurs[] = 'Numéro de téléphone invalide.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreurs[] = 'Adresse email invalide.';
        }

        $cheminDocumentStocke = null;
        $fichier = $requete->fichier('documents');
        if ($fichier !== null && !empty($fichier['name']) && $fichier['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($fichier['error'] !== UPLOAD_ERR_OK) {
                $erreurs[] = "Échec de l'envoi du document justificatif, merci de réessayer.";
            } else {
                $extensionsAutorisees = ['pdf', 'jpg', 'jpeg', 'png'];
                $extension = strtolower(pathinfo($fichier['name'], PATHINFO_EXTENSION));
                if (!in_array($extension, $extensionsAutorisees, true)) {
                    $erreurs[] = 'Le document doit être un PDF, JPG ou PNG.';
                } elseif ($fichier['size'] > 10 * 1024 * 1024) {
                    $erreurs[] = 'Le document ne doit pas dépasser 10 Mo.';
                } else {
                    $dossierUploads = config('chemins.uploads_documents_compagnies');
                    if (!is_dir($dossierUploads)) {
                        mkdir($dossierUploads, 0755, true);
                    }
                    $nomFichier = bin2hex(random_bytes(16)) . '.' . $extension;
                    $cheminComplet = $dossierUploads . '/' . $nomFichier;
                    if (move_uploaded_file($fichier['tmp_name'], $cheminComplet)) {
                        $cheminDocumentStocke = 'uploads/documents-compagnies/' . $nomFichier;
                    } else {
                        $erreurs[] = "Échec de l'enregistrement du document, merci de réessayer.";
                    }
                }
            }
        }

        $succes = false;

        if ($erreurs === []) {
            try {
                Compagnie::creerDemande($nom, $email, $telephone, $ville, $nombreVehicules, $message, $cheminDocumentStocke);
                $succes = true;
            } catch (ErreurMetier $e) {
                $erreurs[] = $e->getMessage();
            }
        }

        return Response::vue('voyageur/rejoindre', [
            'erreurs'    => $erreurs,
            'succes'      => $succes,
            'jetonCsrf'    => Session::genererJetonCsrf(),
        ]);
    }
}
