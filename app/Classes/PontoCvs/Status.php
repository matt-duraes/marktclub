<?php

namespace App\Classes\PontoCvs;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                'solicitado' => 'Solicitado',
                'andamento' => 'Em andamento',
                'recusado' => 'Recusado',
                'aprovado' => 'Aprovado'
            ],
            cor: [
                'solicitado' => 'vermelho',
                'andamento' => 'azul',
                'recusado' => 'preto',
                'aprovado' => 'verde'
            ]
        );
    }
}
