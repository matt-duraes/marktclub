<?php

namespace App\Classes\UsuarioPagamento;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public const ABERTO = 'aberto';
    public const PAGO = 'pago';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::ABERTO => 'Aberto',
                self::PAGO => 'Pago'
            ]
        );
    }
}
