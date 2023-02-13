<?php

namespace App\Classes\SolicitacaoVoucher;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    const STATUS_CRIADO = 'criado';
    const STATUS_VALIDADO = 'validado';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::STATUS_CRIADO => 'Criado',
                self::STATUS_VALIDADO => 'Validado',
            ],
            cor: [
                self::STATUS_CRIADO => 'azul',
                self::STATUS_VALIDADO => 'verde'
            ]
        );
    }
}
