<?php

namespace App\Classes\ComercialEmpresa;

use Status\Status as StatusStatus;

final class EnderecoLocal extends StatusStatus
{
    public const PRINCIPAL = 'principal';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::PRINCIPAL => 'Principal',
            ]
        );
    }
}
