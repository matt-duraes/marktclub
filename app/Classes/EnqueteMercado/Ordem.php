<?php

namespace App\Classes\EnqueteMercado;

use Order\Order;

class Ordem extends Order
{
    /**
     * @param string|null $valor
     */
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_PESQUISA);
        $this->maisNovo();
        $this->maisVelho();
    }
}
