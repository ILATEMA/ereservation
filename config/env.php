<?php

return [

    
    'app' => [
        

        'url'   => null,
        'nom'    => 'e-réservation',
      
        'debug'  => true,
    ],


    'db' => [
        'hote'         => '127.0.0.1',
        'port'          => '3306',
        'nom'           => 'ereservation',
        'utilisateur'    => 'root',
        'mot_de_passe'    => '',
    ],

    
    'smtp' => [
        'hote'              => 'smtp.gmail.com',
        'port'               => 587,
        'utilisateur'         => 'smithdjamil@gmail.com',      // <-- à remplacer
        'mot_de_passe'         => 'wfby zpny oqqc gfef',            // <-- à remplacer
        'email_expediteur'      => 'smithdjamil@gmail.com',      // <-- à remplacer (même adresse que ci-dessus)
        'nom_expediteur'         => 'e-réservation',
    ],


    'fedapay' => [
        'mode'              => 'sandbox',   // 'sandbox' ou 'live'
        'cle_secrete'         => 'sk_sandbox_VNaPRxUvXJzSgETQ05Ob_Lgk',           // <-- à remplacer une fois le compte activé
        'secret_webhook'       => '',           // <-- à remplacer (tableau de bord FedaPay > Webhooks)
    ],

   

    'chemins' => [
        
        'uploads_documents_compagnies' => dirname(__DIR__) . '/public/uploads/documents-compagnies',
    ],


    'metier' => [
        'frais_service_fcfa'         => 250,
        'abonnement_mensuel_fcfa'     => 20000,
        'essai_gratuit_mois'          => 1,
        'hold_minutes'                => 15,
        'nb_places_par_voyage'         => 50,
        'max_sieges_par_commande'      => 4,
    ],

];
