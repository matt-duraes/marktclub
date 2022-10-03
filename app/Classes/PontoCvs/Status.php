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
                'aprovado' => 'Aprovado',
                'recusado' => 'Recusado'
            ],
            cor: [
                'solicitado' => 'vermelho',
                'andamento' => 'azul',
                'aprovado' => 'verde',
                'recusado' => 'preto'
            ]
        );
    }
}
