<?php

namespace App\Classes\ComercialEmpresa;

use Order\Order;

final class Ordem extends Order
{
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_COMERCIAL_EMPRESA);
        $this->maisNovo();
        $this->maisVelho();
    }
}
