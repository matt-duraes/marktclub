<?php

namespace App\Classes\ComunicacaoPopup;

use Order\Order;

class Ordem extends Order
{
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_COMUNICACAO_POPUP);
        $this->padrao('ordem');
        $this->campo('ordem', 'Ordem', 'ordem', 'ASC');
        $this->status();
        $this->maisNovo();
        $this->maisVelho();
    }
}
