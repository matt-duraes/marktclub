<?php

namespace App\Classes\ComercialEmpresa;

use Status\Status as StatusStatus;

final class TipoPagamento extends StatusStatus
{
    public const USUARIO = 'usuario';
    public const FIXO = 'fixo';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::USUARIO => 'Por usuário',
                self::FIXO => 'Valor fixo',
            ]
        );
    }
}
