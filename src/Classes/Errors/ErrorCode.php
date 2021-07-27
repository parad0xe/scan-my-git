<?php


namespace App\Classes\Errors;


class ErrorCode {
    const ERROR_INTERNAL = '[code: 0-i10] Erreur';
    const ERROR_INVALID_ARGUMENTS = '[code: 0-i11] Erreur';
    const ERROR_FORBIDDEN = '[code: 0-i12] Erreur';

    const ERROR_PROCESS_FAILED = '[code: 0-p10] Erreur';
    const ERROR_PROCESS_TIMEOUT = '[code: 0-p11] Erreur';

    const ERROR_GIT_DOWNLOAD_FAILED = '[code: 0-g10] Récupération du projet github échoué';
    const ERROR_GIT_URL = '[code: 0-g10] URL github invalide';
}
