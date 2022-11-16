<?php

namespace App\Classes\ApiApp;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                'ativo' => 'Ativo',
                'inativo' => 'Inativo',
            ],
            cor: [
                'ativo' => 'verde',
                'inativo' => 'azul',
            ],
            numero: [1, 2]
        );
    }
}
