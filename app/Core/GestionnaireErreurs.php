<?php

declare(strict_types=1);

namespace App\Core;

final class GestionnaireErreurs
{
    public static function versReponse(\Throwable $exception): Response
    {
        if ($exception instanceof ErreurMetier) {
            return Response::erreur($exception->getMessage(), $exception->codeHttp, $exception->details);
        }

        error_log('[erreur] ' . $exception->getMessage() . "\n" . $exception->getTraceAsString());

        if (config('app.debug')) {
            return Response::erreur($exception->getMessage(), 500, [$exception->getTraceAsString()]);
        }

        return Response::erreur('Une erreur interne est survenue. Veuillez réessayer.', 500);
    }
}
