<?php

namespace App\Classes\SolicitacaoVoucher;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                'validado' => 'Validado',
                'criado' => 'Criado'
            ],
            cor: [
                'validado' => 'verde',
                'criado' => 'azul'
            ]
        );
    }
}
