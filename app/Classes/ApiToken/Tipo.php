<?php

namespace App\Classes\ApiToken;

use Status\Status as StatusStatus;

final class Tipo extends StatusStatus
{
    const CLUBE = 'clube';
    const PAINEL = 'painel';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::CLUBE => 'Clube',
                self::PAINEL => 'Painel',
            ],
            cor: [
                self::CLUBE => 'verde',
                self::PAINEL => 'azul',
            ]
        );
    }
}
