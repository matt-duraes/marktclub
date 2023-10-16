<?php

namespace App\Classes\ParceiroCupom;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public const NOVO = 'novo';
    public const CANCELADO = 'cancelado';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::NOVO      => 'Novo',
            self::CANCELADO => 'Cancelado'
        ], [
            self::NOVO      => 'azul',
            self::CANCELADO => 'vermelho'
        ]);
    }
}
