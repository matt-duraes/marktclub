<?php

namespace App\Classes\IndicacaoAutomovel;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public const CRIADA = 'criada';
    public const ANDAMENTO = 'andamento';
    public const CONCLUIDA = 'concluida';

    /**
     * @param string|int|null $valor
     */
    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::CRIADA    => 'Criada',
            self::ANDAMENTO => 'Andamento',
            self::CONCLUIDA => 'Concluída'
        ]);
    }
}
