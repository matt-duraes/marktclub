<?php

namespace App\Classes\SolicitacaoSalavip;

use Status\Status;

final class Empresa extends Status
{
    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            [
                'anafe' => 'ANAFE',
                'anape' => 'ANAPE'
            ],
            numero: [2, 66]
        );
    }
}
