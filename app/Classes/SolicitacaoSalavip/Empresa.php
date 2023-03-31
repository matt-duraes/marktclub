<?php

namespace App\Classes\SolicitacaoSalavip;

use Status\Status;

final class Empresa extends Status
{
    public const ANAFE = 'anafe';
    public const ANAPE = 'anape';

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
