<?php

namespace App\Classes\ParceiroIndicacao;

use Status\Status as StatusStatus;

class Status extends StatusStatus
{
    public const NOVO = 'novo';
    public const ANDAMENTO = 'andamento';
    public const CONCLUIDO = 'concluido';
    public const CANCELADO = 'cancelado';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::NOVO        => 'Novo',
            self::ANDAMENTO   => 'Em andamento',
            self::CONCLUIDO   => 'Concluído',
            self::CANCELADO   => 'Cancelado'
        ]);
    }
}
