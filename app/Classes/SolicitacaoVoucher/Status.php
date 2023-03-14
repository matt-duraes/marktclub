<?php

namespace App\Classes\SolicitacaoVoucher;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    const CRIADO = 'criado';
    const VALIDADO = 'validado';
    const VENCIDO = 'vencido';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::CRIADO => 'Criado',
                self::VALIDADO => 'Validado',
                self::VENCIDO => 'vencido',
            ],
            cor: [
                self::CRIADO => 'azul',
                self::VALIDADO => 'verde',
                self::VENCIDO => 'vermelho'
            ]
        );
    }
}
