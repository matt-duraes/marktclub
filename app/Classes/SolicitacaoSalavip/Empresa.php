<?php

namespace App\Classes\SolicitacaoSalavip;

use Status\Status;

final class Empresa extends Status
{
    const ANAFE = 'anafe';
    const ANAPE = 'anape';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            [
                self::ANAFE => 'ANAFE',
                self::ANAPE => 'ANAPE'
            ],
            numero: [2, 66]
        );
    }
}
