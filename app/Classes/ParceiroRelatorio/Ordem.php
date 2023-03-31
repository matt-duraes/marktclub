<?php

namespace App\Classes\ParceiroRelatorio;

use Order\Order;

final class Ordem extends Order
{
    public function __construct(
        protected null|string $valor = null
    ) {
        $this->tabela(TABELA_ANALYTICS_LOJA_VENDA);
        $this->campo('data-asc', 'Maior data', 'data_relatorio', 'DESC');
        $this->campo('data-desc', 'Menor data', 'data_relatorio', 'ASC');
    }
}
