<?php

namespace App\Classes\DownloadPrivado;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    const NOVO = 'novo';
    const VISUALIZADO = 'visualizado';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::NOVO => 'Novo',
                self::VISUALIZADO => 'Visualizado'
            ]
        );
    }
}
