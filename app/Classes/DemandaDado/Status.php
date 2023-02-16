<?php

namespace App\Classes\DemandaDado;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    const NOVA = 'nova';
    const LIBERADA = 'liberada';
    const ANDAMENTO = 'andamento';
    const TESTE = 'teste';
    const CONCLUIDA = 'concluida';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::NOVA => 'Nova',
                self::LIBERADA => 'Liberada',
                self::ANDAMENTO => 'Em andamento',
                self::TESTE => 'Em teste',
                self::CONCLUIDA => 'Concluida'
            ]
        );
    }
}
