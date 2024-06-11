<?php

namespace App\Classes\Votacao\Dado;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public const ATIVO = 'ativo';
    public const INATIVO = 'inativo';
    public const CANCELADO = 'cancelado';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::ATIVO     => 'Ativo',
            self::INATIVO   => 'Inativo',
            self::CANCELADO => 'Cancelado',
        ], [
            self::ATIVO     => 'verde',
            self::INATIVO   => 'vermelho',
            self::CANCELADO => 'preto'
        ]);
    }
}
