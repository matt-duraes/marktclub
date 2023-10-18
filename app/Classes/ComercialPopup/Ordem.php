<?php

namespace App\Classes\ComercialPopup;

use Order\Order;

class Ordem extends Order
{
    /**
     * @param string|null $valor
     */
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_COMERCIAL_POPUP);
        $this->padrao('ordem');
        $this->campo('ordem', 'Ordem', 'ordem', 'ASC');
        $this->status();
        $this->maisNovo();
        $this->maisVelho();
    }
}
