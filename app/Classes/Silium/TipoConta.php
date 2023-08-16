<?php

namespace App\Classes\Silium;

use Status\Status;

class TipoConta extends Status
{
    public const CONTA_CORRENTE = 'conta_corrente';
    public const CONTA_POUPANCA = 'conta_poupanca';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::CONTA_CORRENTE => 'Conta Corrente',
            self::CONTA_POUPANCA => 'Conta Poupança'
        ]);
    }
}
