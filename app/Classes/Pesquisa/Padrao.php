<?php

namespace App\Classes\Pesquisa;

use Status\Status;

class Padrao extends Status
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
            self::SIM => 'Sim',
            self::NAO => 'Não'
        ]);
    }
}
