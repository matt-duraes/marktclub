<?php

namespace App\Classes\DemandaDado;

use Order\Order;

final class Ordem extends Order
{
    public function __construct(
        protected null|string $valor = null
    ) {
        $this->tabela(TABELA_DEMANDA_DADO);
        $this->maisNovo();
        $this->campo('ordem', 'Ordem', 'ordem', 'ASC');
    }
}
