<?php

namespace App\Classes\Automovel\Automovel;

use Order\Order;

final class Ordem extends Order
{
    public function __construct(
        protected null|string $valor = null
    ) {
        $this->tabela(TABELA_CARRO);
        $this->maisNovo();
        $this->maisVelho();
    }
}
