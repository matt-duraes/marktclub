<?php

namespace App\Classes\DemandaDado;

use Status\Status as StatusStatus;

final class Area extends StatusStatus
{
    public const TECNOLOGIA = 'tecnologia';
    public const CRIACAO = 'criacao';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::TECNOLOGIA => 'Tecnologia',
                self::CRIACAO => 'Criação'
            ]
        );
    }
}
