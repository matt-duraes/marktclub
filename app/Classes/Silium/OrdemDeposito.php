<?php

namespace App\Classes\Silium;

use Order\Order;

final class OrdemDeposito extends Order
{
    /**
     * @param string|null $valor
     */
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_SILIUM_DEPOSITO);
        $this->padrao('status');
        $this->status();
        $this->maisNovo();
        $this->maisVelho();
    }
}
