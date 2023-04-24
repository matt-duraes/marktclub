<?php

namespace App\Classes\SolicitacaoVoucher;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public const CRIADO = 'criado';
    public const VALIDADO = 'validado';
    public const VENCIDO = 'vencido';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::CRIADO   => 'Criado',
            self::VALIDADO => 'Validado',
            self::VENCIDO  => 'Vencido'
        ], [
            self::CRIADO   => 'azul',
            self::VALIDADO => 'verde',
            self::VENCIDO  => 'vermelho'
        ]);
    }
}
