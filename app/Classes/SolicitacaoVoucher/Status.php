<?php

namespace App\Classes\SolicitacaoVoucher;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public const CRIADO = 'criado';
    public const VALIDADO = 'validado';
    public const VENCIDO = 'vencido';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::CRIADO => 'Criado',
                self::VALIDADO => 'Validado',
                self::VENCIDO => 'Vencido',
            ],
            cor: [
                self::CRIADO => 'azul',
                self::VALIDADO => 'verde',
                self::VENCIDO => 'vermelho'
            ]
        );
    }
}
