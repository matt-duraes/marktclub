<?php

namespace App\Classes\IndicacaoParceiro;

use Status\Status as StatusStatus;

class Status extends StatusStatus
{
    public const CRIADA = 'criada';
    public const ANDAMENTO = 'andamento';
    public const CONCLUIDA = 'concluida';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::CRIADA       => 'Criada',
            self::ANDAMENTO    => 'Andamento',
            self::CONCLUIDA    => 'Concluída'
        ]);
    }
}
