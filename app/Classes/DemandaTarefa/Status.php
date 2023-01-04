<?php

namespace App\Classes\DemandaTarefa;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                'backlog' => 'Demandas',
                'todo' => 'Liberada',
                'doing' => 'Em andamento',
                'review' => 'Em revisão',
                'test' => 'Em teste',
                'done' => 'Em produção'
            ]
        );
    }
}
