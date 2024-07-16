<?php

namespace App\Classes\SiliumDeposito;

use Status\Status;

class TipoOperacao extends Status
{
    public const SAQUE = 'saque';
    public const DEPOSITO = 'deposito';

    /**
     * @param string|int|null $valor
     */
    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::SAQUE    => 'Solicitação de Saque',
            self::DEPOSITO => 'Depósito'
        ]);
    }
}
