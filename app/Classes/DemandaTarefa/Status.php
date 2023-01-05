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
                'backlog' => 'Backlog',
                'todo' => 'To Do',
                'doing' => 'Doing',
                'review' => 'Review',
                'test' => 'Test',
                'done' => 'Done'
            ]
        );
    }
}
