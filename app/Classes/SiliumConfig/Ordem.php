<?php

namespace App\Classes\SiliumConfig;

use Order\Order;

class Ordem extends Order
{
    /**
     * @param string|null $valor
     */
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_SILIUM_CONFIG);
        $this->maisNovo();
        $this->maisVelho();
    }
}
