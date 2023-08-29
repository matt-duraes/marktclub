<?php

namespace App\Classes\EnqueteSatisfacao;

use Status\Status as StatusStatus;

final class Navegar extends StatusStatus
{
    public const SIM = 'sim';
    public const NAO = 'nao';

    /**
     * @param string|int|null $valor
     */
    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::SIM => 'Fácil de navegar',
            self::NAO => 'Difícil de navegar'
        ]);
    }
}
