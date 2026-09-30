<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Exception à lever depuis un Model ou un Controller quand une règle
 * métier est violée (siège déjà pris, commande introuvable, etc.).
 * GestionnaireErreurs la traduit automatiquement en Response HTTP.
 */
class ErreurMetier extends \RuntimeException
{
    /** @param string[] $details */
    public function __construct(
        string $message,
        public readonly int $codeHttp = 400,
        public readonly array $details = [],
    ) {
        parent::__construct($message);
    }
}
