<?php

namespace App\Classes\Automovel\Versao;

use Order\Order;

final class Ordem extends Order
{
    public function __construct(
        protected null|string $valor = null
    ) {
        $this->tabela(TABELA_CARRO_MODELO);
        $this->maisNovo();
        $this->maisVelho();
    }
}
