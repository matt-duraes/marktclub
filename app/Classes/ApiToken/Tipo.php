<?php

namespace App\Classes\ApiToken;

use Status\Status as StatusStatus;

final class Tipo extends StatusStatus
{
    const TIPO_CLUBE = 1;
    const TIPO_PAINEL = 2;

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                'Clube',
                'Painel',
            ],
            cor: [
                'clube' => 'verde',
                'painel' => 'azul',
            ]
        );
    }
}
