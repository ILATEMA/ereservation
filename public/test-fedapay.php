<?php

declare(strict_types=1);

$config = require __DIR__ . '/../config/env.php';

$cle = $config['fedapay']['cle_secrete'] ?? '';

if ($cle === '') {
    die('ERREUR : clé secrète FedaPay absente.');
}

$url = 'https://sandbox-api.fedapay.com/v1/transactions';

$donnees = [
    'description' => 'Test e-reservation',
    'amount' => 100,
    'currency' => [
        'iso' => 'XOF'
    ],
    'callback_url' => 'http://localhost/ereservationZ/public/paiement/retour?commande=test',
    'customer' => [
        'firstname' => 'Test',
        'lastname' => 'Client',
        'email' => 'test@example.com',
        'phone_number' => [
            'number' => '97000000',
            'country' => 'bj'
        ]
    ]
];

$ch = curl_init($url);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 20,
    CURLOPT_CONNECTTIMEOUT => 10,

    CURLOPT_POST => true,

    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . $cle,
        'Accept: application/json',
        'Content-Type: application/json',
    ],

    CURLOPT_POSTFIELDS => json_encode(
        $donnees,
        JSON_UNESCAPED_UNICODE
    ),
]);

$reponse = curl_exec($ch);

$curlError = curl_error($ch);
$curlErrno = curl_errno($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

echo '<h1>Test création transaction FedaPay</h1>';

echo '<p><strong>HTTP :</strong> ' . $httpCode . '</p>';
echo '<p><strong>cURL errno :</strong> ' . $curlErrno . '</p>';
echo '<p><strong>cURL erreur :</strong> ' . htmlspecialchars($curlError) . '</p>';

echo '<h2>Réponse FedaPay</h2>';
echo '<pre>';
echo htmlspecialchars($reponse ?: '(aucune réponse)');
echo '</pre>';