<?php

namespace App\Classes\Saude;

use Status\Status as StatusStatus;

class Status extends StatusStatus
{
    public const NOVO = 'novo';
    public const ENVIADO = 'enviado';
    public const CONTRATADO = 'contratado';
    public const CANCELADO = 'cancelado';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::NOVO       => 'Novo',
            self::ENVIADO    => 'Enviado para operadora',
            self::CONTRATADO => 'Contratado',
            self::CANCELADO  => 'Cancelado'
        ], [
            self::NOVO       => 'vermelho',
            self::ENVIADO    => 'azul',
            self::CONTRATADO => 'verde',
            self::CANCELADO  => 'cinza'
        ]);
    }
}
