<?php

namespace App\Classes\PontoCvs;

use Order\Order;

final class Ordem extends Order
{
    public function __construct(
        protected null|string $valor = null
    ) {
        $this->tabela(TABELA_PONTO_CVS);
        $this->maisNovo();
        $this->maisVelho();
    }
}
