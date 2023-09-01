<?php

namespace App\Classes\SolicitacaoLoja;

use Status\Status as StatusStatus;

class Status extends StatusStatus
{
    public const NOVO = 'novo';
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
            self::NOVO      => 'Novo',
            self::ANDAMENTO => 'Em andamento',
            self::CONCLUIDO => 'Concluído',
            self::CANCELADO => 'Cancelado'
        ], [
            self::NOVO      => 'azul',
            self::ANDAMENTO => 'amarelo',
            self::CONCLUIDO => 'verde',
            self::CANCELADO => 'vermelho'
        ]);
    }
}
