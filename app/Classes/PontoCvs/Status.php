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
                'aprovado' => 'Aprovado',
                'recusado' => 'Recusado'
            ],
            cor: [
                'solicitado' => 'azul',
                'aprovado' => 'verde',
                'recusado' => 'preto'
            ]
        );
    }
}
