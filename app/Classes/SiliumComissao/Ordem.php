<?php

namespace App\Classes\SiliumComissao;

use Order\Order;

class Ordem extends Order
{
    /**
     * @param string|null $valor
     */
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_SILIUM_COMISSAO);
        $this->padrao('data_criacao');
        $this->status();
        $this->maisNovo();
        $this->maisVelho();
    }
}
