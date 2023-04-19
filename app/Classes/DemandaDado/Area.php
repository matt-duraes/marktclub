<?php

namespace App\Classes\DemandaDado;

use Status\Status as StatusStatus;

final class Area extends StatusStatus
{
    public const TI = 'ti';
    public const CRIACAO = 'criacao';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::TI => 'TI',
                self::CRIACAO => 'Criação'
            ]
        );
    }
}
