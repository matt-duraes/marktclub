<?php

namespace App\Classes\EnqueteSatisfacao;

use Order\Order;

class Ordem extends Order
{
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_ENQUETE);
        $this->maisNovo();
        $this->maisVelho();
    }
}
