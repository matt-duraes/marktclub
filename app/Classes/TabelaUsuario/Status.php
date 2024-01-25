<?php

namespace App\Classes\TabelaUsuario;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public const NOVO = 'novo';
    public const PROCESSADO = 'processado';
    public const ERRO = 'erro';
    public const CANCELADO = 'cancelado';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::NOVO       => 'Novo',
            self::PROCESSADO => 'Processado',
            self::ERRO       => 'Erro',
            self::CANCELADO  => 'Cancelado'
        ], [
            self::NOVO       => 'azul',
            self::PROCESSADO => 'verde',
            self::ERRO       => 'vermelho',
            self::CANCELADO  => 'cinza'
        ]);
    }
}
