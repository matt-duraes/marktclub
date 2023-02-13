<?php

namespace App\Classes\SolicitacaoSalavip;

use Status\Status;

final class Empresa extends Status
{
    const EMPRESA_ANAFE = 'anafe';
    const EMPRESA_ANAPE = 'anape';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            [
                self::EMPRESA_ANAFE => 'ANAFE',
                self::EMPRESA_ANAPE => 'ANAPE'
            ],
            numero: [2, 66]
        );
    }
}
