<?php

namespace App\Classes\ApiToken;

use Status\Status as StatusStatus;

final class Tipo extends StatusStatus
{
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
