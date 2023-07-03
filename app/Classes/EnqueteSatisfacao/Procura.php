<?php

namespace App\Classes\EnqueteSatisfacao;

use Status\Status as StatusStatus;

final class Procura extends StatusStatus
{
    public const SIM = 'sim';
    public const NAO = 'nao';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::SIM    => 'Acha o que procura',
            self::NAO    => 'Não acha o que procura'
        ]);
    }
}
