<?php

namespace App\Classes\DemandaDado;

use Status\Status as StatusStatus;

final class Area extends StatusStatus
{
    public const TECNOLOGIA = 'tecnologia';
    public const CRIACAO = 'criacao';
    public const CONVENIO = 'convenio';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::TECNOLOGIA => 'Tecnologia',
            self::CRIACAO    => 'Criação',
            self::CONVENIO   => 'Convênio',
        ]);
    }
}
