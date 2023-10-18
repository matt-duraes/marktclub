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
    public const CANCELADA = 'cancelada';
    public const BLOQUEADA = 'bloqueada';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::NOVA      => 'Nova',
            self::LIBERADA  => 'Liberada',
            self::ANDAMENTO => 'Em andamento',
            self::TESTE     => 'Em teste',
            self::CONCLUIDA => 'Concluida',
            self::CANCELADA => 'Cancelada',
            self::BLOQUEADA => 'Bloqueada',
        ]);
    }
}
