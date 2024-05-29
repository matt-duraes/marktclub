<?php

namespace App\Classes\Silium;

use Status\Status;

class Tipo extends Status
{
    public const SAQUE = 'saque';
    public const DEPOSITO = 'deposito';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::SAQUE    => 'Solicitação de Saque',
            self::DEPOSITO => 'Depósito'
        ]);
    }
}
