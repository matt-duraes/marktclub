<?php

namespace App\Classes\UsuarioPagamento;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public const ABERTO = 'aberto';
    public const PAGO = 'pago';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::ABERTO => 'Aberto',
            self::PAGO   => 'Pago'
        ]);
    }
}
