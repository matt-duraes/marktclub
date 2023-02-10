<?php

namespace App\Classes\UsuarioPagamento;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    const STATUS_ABERTO = 'aberto';
    const STATUS_PAGO = 'pago';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::STATUS_ABERTO => 'Aberto',
                self::STATUS_PAGO => 'Pago'
            ]
        );
    }
}
