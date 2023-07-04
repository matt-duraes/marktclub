<?php

namespace App\Classes\DemandaTarefa;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public const AGUARDANDO = 'aguardando';
    public const ANDAMENTO = 'andamento';
    public const CONCLUIDA = 'concluida';
    public const CANCELADA = 'cancelada';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::AGUARDANDO  => 'Aguardando',
            self::ANDAMENTO   => 'Em andamento',
            self::CONCLUIDA   => 'Concluida',
            self::CANCELADA   => 'Cancelada'
        ]);
    }
}
