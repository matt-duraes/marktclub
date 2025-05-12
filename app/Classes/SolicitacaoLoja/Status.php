<?php

namespace App\Classes\SolicitacaoLoja;

use Status\Status as StatusStatus;

class Status extends StatusStatus
{
    public const SEM_VINCULO = 'sem-vinculo';
    public const ANDAMENTO = 'andamento';
    public const CONCLUIDO = 'concluido';
    public const CANCELADO = 'cancelado';

    /**
     * @param string|int|null $valor
     */
    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::SEM_VINCULO => 'Sem Vínculo',
            self::ANDAMENTO   => 'Em Prospeccão',
            self::CONCLUIDO   => 'Concluído',
            self::CANCELADO   => 'Cancelado'
        ], [
            self::SEM_VINCULO => 'cinza',
            self::ANDAMENTO   => 'amarelo',
            self::CONCLUIDO   => 'verde',
            self::CANCELADO   => 'vermelho'
        ]);
    }
}
