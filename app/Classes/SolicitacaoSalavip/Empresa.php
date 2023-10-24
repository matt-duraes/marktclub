<?php

namespace App\Classes\SolicitacaoSalavip;

use Status\Status;

final class Empresa extends Status
{
    public const ANAFE = 'anafe';
    public const ANAPE = 'anape';

    /**
     * @param string|int|null $valor
     */
    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::ANAFE => 'ANAFE',
            self::ANAPE => 'ANAPE'
        ], numero: [2, 66]);
    }
}
