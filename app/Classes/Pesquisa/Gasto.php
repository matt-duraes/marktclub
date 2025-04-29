<?php

namespace App\Classes\Pesquisa;

use Status\Status;

class Gasto extends Status
{
    public const MIL = 'mil';
    public const DOIS_MIL = 'dois_mil';
    public const TRES_MIL = 'tres_mil';

    /**
     * @param string|int|null $valor
     */
    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::MIL      => 'Até R$ 1.000,00',
            self::DOIS_MIL => 'Entre R$ 1.001 e R$ 2.000,00',
            self::TRES_MIL => 'Mais de R$ 3.000,00'
        ]);
    }
}
