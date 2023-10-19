<?php

namespace App\Classes\Automovel\Modelo;

use Order\Order;

final class Ordem extends Order
{
    /**
     * @param string|null $valor
     */
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_AUTOMOVEL_MODELO);
        $this->maisNovo();
        $this->maisVelho();
    }
}
