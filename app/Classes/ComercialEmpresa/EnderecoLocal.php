<?php

namespace App\Classes\ComercialEmpresa;

use Status\Status as StatusStatus;

final class EnderecoLocal extends StatusStatus
{
    public const PRINCIPAL = 'principal';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::PRINCIPAL => 'Principal'
        ]);
    }
}
