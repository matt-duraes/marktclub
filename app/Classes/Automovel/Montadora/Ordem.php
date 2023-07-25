<?php

namespace App\Classes\Automovel\Montadora;

use Order\Order;

final class Ordem extends Order
{
    public function __construct(
        protected null|string $valor = null
    ) {
        $this->tabela(TABELA_CARRO_MENU);
        $this->maisNovo();
        $this->maisVelho();
    }
}
