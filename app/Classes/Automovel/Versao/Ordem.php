<?php

namespace App\Classes\Automovel\Versao;

use Order\Order;

final class Ordem extends Order
{
    public function __construct(
        protected null|string $valor = null
    ) {
        $this->tabela(TABELA_AUTOMOVEL_VERSAO);
        $this->maisNovo();
        $this->maisVelho();
    }
}
