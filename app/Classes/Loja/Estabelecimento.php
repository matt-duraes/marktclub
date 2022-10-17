<?php

namespace App\Classes\Loja;

use Status\Status;

final class Estabelecimento extends Status
{
    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                'fisico' => 'Físico',
                'online' => 'On-line'
            ]
        );
    }
}
