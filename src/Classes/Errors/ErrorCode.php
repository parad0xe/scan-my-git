<?php

namespace App\Classes\Errors;

/**
 * Class to index our error codes
 * Class ErrorCode.
 * @package App\Classes\Errors
 */
class ErrorCode {
    public const ERROR_INTERNAL = '[code: 0-i10] Erreur';
    public const ERROR_INVALID_ARGUMENTS = '[code: 0-i11] Erreur';
    public const ERROR_FORBIDDEN = '[code: 0-i12] Erreur';

    public const ERROR_PROCESS_FAILED = '[code: 0-p10] Erreur';
    public const ERROR_PROCESS_TIMEOUT = '[code: 0-p11] Erreur';

    public const ERROR_GIT_DOWNLOAD_FAILED = '[code: 0-g10] Récupération du projet github échoué';
    public const ERROR_GIT_URL = '[code: 0-g10] URL github invalide';
}
