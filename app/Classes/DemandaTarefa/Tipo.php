<?php

namespace App\Classes\DemandaTarefa;

use Status\Status;

final class Tipo extends Status
{
    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                'back-end' => 'Back-end',
                'front-end' => 'Front-End',
                'criacao' => 'Criação',
                'app' => 'APP',
                'banco' => 'Banco de dados',
                'infra' => 'Infraestrutura'
            ]
        );
    }
}
