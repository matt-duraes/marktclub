<?php

namespace App\Classes\SolicitacaoCredito;

use Status\Status;

final class Operadora extends Status
{
    public const SICOOB = 'sicoob';

    /**
     * @param string|int|null $valor
     */
    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::SICOOB => 'Sicoob'
        ]);
    }
}
