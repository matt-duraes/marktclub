<?php

namespace App\Classes\DemandaDado;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public const NOVA = 'nova';
    public const LIBERADA = 'liberada';
    public const ANDAMENTO = 'andamento';
    public const TESTE = 'teste';
    public const CONCLUIDA = 'concluida';

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
