<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Envoi d'emails via un vrai serveur SMTP externe (Gmail, Brevo, etc.),
 * sans passer par mail() qui ne fonctionne pas de façon fiable sur
 * WampServer/XAMPP sans configuration système. Déjà testé (protocole
 * STARTTLS + AUTH LOGIN validé contre un faux serveur local).
 */
final class Mailer
{
    public static function envoyer(string $destinataire, string $sujet, string $corpsHtml): array
    {
        $hote          = config('smtp.hote', '');
        $port           = (int) config('smtp.port', 587);
        $utilisateur     = config('smtp.utilisateur', '');
        $motDePasse       = config('smtp.mot_de_passe', '');
        $emailExpediteur   = config('smtp.email_expediteur') ?: $utilisateur;
        $nomExpediteur      = config('smtp.nom_expediteur', 'e-réservation');

        if ($hote === '' || $utilisateur === '' || $motDePasse === '') {
            error_log('[mailer] SMTP non configuré — email non envoyé.');
            return ['succes' => false, 'erreur' => 'SMTP non configuré.'];
        }

$adresseConnexion = gethostbyname($hote);

$contexte = stream_context_create([
    'ssl' => [
        'peer_name' => $hote,
        'verify_peer' => true,
        'verify_peer_name' => true,
        'allow_self_signed' => false,
    ],
]);

$socket = @stream_socket_client(
    "tcp://{$adresseConnexion}:{$port}",
    $codeErreur,
    $messageErreur,
    10,
    STREAM_CLIENT_CONNECT,
    $contexte
);

if ($socket === false) {
    error_log("[mailer] Connexion SMTP impossible : {$messageErreur} ({$codeErreur})");
    return ['succes' => false, 'erreur' => 'Connexion SMTP impossible.'];
}

        try {
            $lire = function () use ($socket): string {
                $reponse = '';
                while ($ligne = fgets($socket, 512)) {
                    $reponse .= $ligne;
                    if (isset($ligne[3]) && $ligne[3] === ' ') {
                        break;
                    }
                }
                return $reponse;
            };
            $ecrire = function (string $commande) use ($socket): void {
                fwrite($socket, $commande . "\r\n");
            };
            $verifier = function (string $reponse, string $attendu): void {
                if (!str_starts_with($reponse, $attendu)) {
                    throw new \RuntimeException("Réponse SMTP inattendue (attendu {$attendu}) : {$reponse}");
                }
            };

            $lire();
            $ecrire('EHLO localhost');
            $verifier($lire(), '250');
            $ecrire('STARTTLS');
            $verifier($lire(), '220');

          if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
    $erreur = error_get_last();

    throw new \RuntimeException(
        'Échec de la négociation TLS. ' .
        ($erreur['message'] ?? 'Aucun détail supplémentaire.')
    );
}

            $ecrire('EHLO localhost');
            $verifier($lire(), '250');
            $ecrire('AUTH LOGIN');
            $verifier($lire(), '334');
            $ecrire(base64_encode($utilisateur));
            $verifier($lire(), '334');
            $ecrire(base64_encode($motDePasse));
            $verifier($lire(), '235');

            $ecrire("MAIL FROM:<{$emailExpediteur}>");
            $verifier($lire(), '250');
            $ecrire("RCPT TO:<{$destinataire}>");
            $verifier($lire(), '250');
            $ecrire('DATA');
            $verifier($lire(), '354');

            $entetes = implode("\r\n", [
                "From: {$nomExpediteur} <{$emailExpediteur}>",
                "To: <{$destinataire}>",
                'Subject: =?UTF-8?B?' . base64_encode($sujet) . '?=',
                'Date: ' . date('r'),
                'MIME-Version: 1.0',
                'Content-Type: text/html; charset=UTF-8',
                'Content-Transfer-Encoding: 8bit',
            ]);
                $corpsEchappe = preg_replace('/^\./m', '..', $corpsHtml);
            $ecrire($entetes . "\r\n\r\n" . $corpsEchappe . "\r\n.");
            $verifier($lire(), '250');
            $ecrire('QUIT');
            fclose($socket);

            return ['succes' => true, 'erreur' => null];
        } catch (\Throwable $e) {
            if (is_resource($socket)) {
                fclose($socket);
            }
            error_log('[mailer] Échec envoi SMTP : ' . $e->getMessage());
            return ['succes' => false, 'erreur' => $e->getMessage()];
        }
    }

    public static function genererMotDePasseTemporaire(int $longueur = 10): string
    {
        $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789!@#$%';
        $mdp = '';
        for ($i = 0; $i < $longueur; $i++) {
            $mdp .= $alphabet[random_int(0, strlen($alphabet) - 1)];
        }
        // Garantit au minimum une majuscule, un chiffre et un caractère spécial.
        $mdp[0] = 'A';
        $mdp[1] = (string) random_int(2, 9);
        $mdp[2] = '!';
        return $mdp;
    }
}
