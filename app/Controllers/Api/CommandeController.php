<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Core\Aide;
use App\Core\ErreurMetier;
use App\Core\FedaPay;
use App\Core\Paiement;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Models\Commande;

final class CommandeController
{
    private function exigerCsrf(Request $requete): void
    {
        if (!Session::verifierJetonCsrf($requete->entree('csrf') ?? $requete->entete('X-CSRF-Token'))) {
            throw new ErreurMetier('Jeton CSRF invalide ou session expirée.', 419);
        }
    }

    public function creer(Request $requete): Response
    {
        $this->exigerCsrf($requete);
        $voyageId          = (int) $requete->entree('voyageId', 0);
        $sieges              = $requete->entree('sieges', []);
        $passagers            = $requete->entree('passagers', []);
        $telephoneContact       = Aide::nettoyerTelephone((string) $requete->entree('telephoneContact', ''));
        $emailContact            = $requete->entree('emailContact');
        $emailContact             = ($emailContact !== null && trim((string) $emailContact) !== '') ? trim((string) $emailContact) : null;

        $erreurs = [];
        $maxSieges = (int) config('metier.max_sieges_par_commande');

        if ($voyageId <= 0) {
            $erreurs[] = 'voyageId invalide.';
        }
        if (!is_array($sieges) || count($sieges) === 0) {
            $erreurs[] = 'Au moins un siège doit être sélectionné.';
        } elseif (count($sieges) > $maxSieges) {
            $erreurs[] = "Maximum {$maxSieges} sièges par commande.";
        } else {
            $sieges = array_map('intval', $sieges);
            if (count($sieges) !== count(array_unique($sieges))) {
                $erreurs[] = 'Un même siège ne peut pas être sélectionné deux fois.';
            }
            foreach ($sieges as $s) {
                if ($s < 1 || $s > (int) config('metier.nb_places_par_voyage')) {
                    $erreurs[] = "Numéro de siège invalide : {$s}.";
                }
            }
        }
        if (!is_array($passagers) || count($passagers) !== count($sieges)) {
            $erreurs[] = 'Il doit y avoir exactement un nom de passager par siège.';
        } else {
            foreach ($passagers as $nom) {
                if (trim((string) $nom) === '') {
                    $erreurs[] = 'Le nom de chaque passager est obligatoire.';
                    break;
                }
            }
        }
        if (!preg_match('/^\+?[0-9]{8,15}$/', $telephoneContact)) {
            $erreurs[] = 'Le numéro de téléphone de contact est invalide.';
        }
        if ($emailContact !== null && !filter_var($emailContact, FILTER_VALIDATE_EMAIL)) {
            $erreurs[] = "L'adresse email est invalide.";
        }

        if ($erreurs !== []) {
            throw new ErreurMetier('Données invalides.', 422, $erreurs);
        }

        $resultat = Commande::creer($voyageId, $sieges, $passagers, $telephoneContact, $emailContact);

        // Mode démo : si FedaPay n'est pas configuré, on informe le
        // front-end qu'il peut directement demander la confirmation
        // simulée (voir FedaPayController::initier).
        $resultat['modeDemo'] = !FedaPay::estConfigure();

        return Response::json($resultat, 201);
    }
}
