<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Intégration FedaPay — voir config/env.php pour les clés.
 * ⚠️ Écrit sans accès à la doc FedaPay en direct : vérifie sur
 * https://docs.fedapay.com avant la mise en prod (nom exact de l'en-tête
 * de signature webhook, valeurs exactes des statuts).
 */
final class FedaPay
{
    public static function estConfigure(): bool
    {
        return (string) config('fedapay.cle_secrete', '') !== '';
    }

    private static function baseUrl(): string
    {
        return config('fedapay.mode') === 'live'
            ? 'https://api.fedapay.com/v1'
            : 'https://sandbox-api.fedapay.com/v1';
    }

    private static function requete(string $methode, string $chemin, array $donnees = []): array
    {
        $cle = config('fedapay.cle_secrete', '');
        if ($cle === '') {
            return ['succes' => false, 'erreur' => 'Clé FedaPay non configurée.', 'donnees' => null];
        }

        $ch = curl_init(self::baseUrl() . $chemin);
        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT         => 15,
            CURLOPT_HTTPHEADER      => [
                'Authorization: Bearer ' . $cle,
                'Content-Type: application/json',
                'Accept: application/json',
            ],
        ];
        if ($methode === 'POST') {
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = json_encode($donnees, JSON_UNESCAPED_UNICODE);
        } else {
            $options[CURLOPT_HTTPGET] = true;
        }
        curl_setopt_array($ch, $options);
        $brut = curl_exec($ch);
        $erreurCurl = curl_error($ch);
        $codeHttp = curl_getinfo($ch, CURLINFO_HTTP_CODE);
       
        if ($brut === false) {
            return ['succes' => false, 'erreur' => "Connexion FedaPay impossible : {$erreurCurl}", 'donnees' => null];
        }

        $decode = json_decode($brut, true);
        if ($codeHttp < 200 || $codeHttp >= 300) {
            error_log("[fedapay] HTTP {$codeHttp} : {$brut}");
            return ['succes' => false, 'erreur' => $decode['message'] ?? "Erreur FedaPay (HTTP {$codeHttp}).", 'donnees' => $decode];
        }

        return ['succes' => true, 'erreur' => null, 'donnees' => $decode];
    }

    public static function creerTransaction(
        int $montantFcfa,
        string $description,
        string $nomClient,
        ?string $emailClient,
        string $telephoneClient,
        string $callbackUrl
    ): array {
        $resultat = self::requete('POST', '/transactions', [
            'description'  => $description,
            'amount'        => $montantFcfa,
            'currency'      => ['iso' => 'XOF'],
            'callback_url'   => $callbackUrl,
            'customer'       => [
                'firstname'   => $nomClient,
                'lastname'     => '.',
                'email'        => $emailClient,
                'phone_number'  => ['number' => $telephoneClient, 'country' => 'bj'],
            ],
        ]);

        if (!$resultat['succes']) {
            return ['succes' => false, 'erreur' => $resultat['erreur'], 'transactionId' => null];
        }

        $id = $resultat['donnees']['v1/transaction']['id']
            ?? $resultat['donnees']['transaction']['id']
            ?? $resultat['donnees']['id']
            ?? null;

        if ($id === null) {
            return ['succes' => false, 'erreur' => 'Réponse FedaPay inattendue (id introuvable).', 'transactionId' => null];
        }

        return ['succes' => true, 'erreur' => null, 'transactionId' => (int) $id];
    }

    public static function genererLienPaiement(int $transactionId): array
    {
        $resultat = self::requete('POST', "/transactions/{$transactionId}/token");
        if (!$resultat['succes']) {
            return ['succes' => false, 'erreur' => $resultat['erreur'], 'url' => null];
        }
        $url = $resultat['donnees']['url']
            ?? $resultat['donnees']['v1/token']['url']
            ?? $resultat['donnees']['token']['url']
            ?? null;

        if ($url === null) {
            return ['succes' => false, 'erreur' => 'URL de paiement introuvable dans la réponse.', 'url' => null];
        }
        return ['succes' => true, 'erreur' => null, 'url' => $url];
    }

    public static function recupererTransaction(int $transactionId): array
    {
        $resultat = self::requete('GET', "/transactions/{$transactionId}");
        if (!$resultat['succes']) {
            return ['succes' => false, 'erreur' => $resultat['erreur'], 'transaction' => null];
        }
        $transaction = $resultat['donnees']['v1/transaction'] ?? $resultat['donnees']['transaction'] ?? $resultat['donnees'] ?? null;
        return ['succes' => true, 'erreur' => null, 'transaction' => $transaction];
    }

    public static function verifierSignatureWebhook(string $corpsBrut, ?string $signatureRecue): bool
    {
        $secret = config('fedapay.secret_webhook', '');
        if ($secret === '' || $signatureRecue === null) {
            return false;
        }
        return hash_equals(hash_hmac('sha256', $corpsBrut, $secret), $signatureRecue);
    }
}
