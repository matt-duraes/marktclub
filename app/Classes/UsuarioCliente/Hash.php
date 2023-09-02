<?php

namespace App\Classes\UsuarioCliente;

use Status\Status;

final class Hash extends Status
{
    public const ATIVAR = 'ativar';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::ATIVAR     => 'Ativar'
        ]);
    }
}
