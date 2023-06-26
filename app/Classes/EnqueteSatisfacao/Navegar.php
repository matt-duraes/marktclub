<?php

namespace App\Classes\EnqueteSatisfacao;

use Status\Status as StatusStatus;

final class Navegar extends StatusStatus
{
    public const SIM = 'sim';
    public const NAO = 'nao';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::SIM => 'Fácil navegar',
            self::NAO    => 'Difícil navegar'
        ]);
    }
}
