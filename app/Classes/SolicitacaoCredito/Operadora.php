<?php

namespace App\Classes\SolicitacaoCredito;

use Status\Status;

class Operadora extends Status
{
    public const SICOOB = 'sicoob';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::SICOOB => 'Sicoob'
        ]);
    }
}
