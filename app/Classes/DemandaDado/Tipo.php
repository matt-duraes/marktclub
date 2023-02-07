<?php

namespace App\Classes\DemandaDado;

use Status\Status;

final class Tipo extends Status
{
    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                'novo-cliente' => 'Novo Cliente',
                'novo-associacao' => 'Site associação',
                'outro' => 'Outro',
                'bug-clube' => 'Bug no clube',
                'bug-associacao' => 'Bug na associação',
                'bug-painel' => 'Bug no painel',
                'bug-app' => 'Bug no APP',
                'bug-outro' => 'Bug outros',
                'feature' => 'Feature'
            ]
        );
    }
}
