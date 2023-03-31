<?php

namespace App\Classes\ApiToken;

use Status\Status as StatusStatus;

final class Tipo extends StatusStatus
{
    public const CLUBE = 'clube';
    public const PAINEL = 'painel';

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
