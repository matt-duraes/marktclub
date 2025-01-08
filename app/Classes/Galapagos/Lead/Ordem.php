<?php

namespace App\Classes\Galapagos\Lead;

use Order\Order;

final class Ordem extends Order
{
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_GALAPAGOS_LEAD);
        $this->campo('status', 'Status', 'status', 'ASC');
        $this->maisNovo();
        $this->maisVelho();
    }
}
