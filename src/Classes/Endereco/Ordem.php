<?php

namespace System\Classes\Endereco;

use Order\Order;

class Ordem extends Order
{
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_SISTEMA_ENDERECO);
        $this->maisNovo();
        $this->maisVelho();
    }
}
