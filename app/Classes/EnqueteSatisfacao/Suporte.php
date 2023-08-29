<?php

namespace App\Classes\EnqueteSatisfacao;

use Status\Status as StatusStatus;

final class Suporte extends StatusStatus
{
    public const SIM = 'sim';
    public const NAO = 'nao';
    public const NUNCA = 'nunca';

    /**
     * @param string|int|null $valor
     */
    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::SIM   => 'Já pediu ajuda ao suporte',
            self::NAO   => 'Não pediu ajuda ao suporte',
            self::NUNCA => 'Nunca precisou pedir'
        ]);
    }
}
