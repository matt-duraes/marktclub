<?php

namespace App\Classes\DemandaDado;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                'nova' => 'Nova',
                'liberada' => 'Liberada',
                'andamento' => 'Em andamento',
                'finalizada' => 'Finalizada'
            ]
        );
    }
}
