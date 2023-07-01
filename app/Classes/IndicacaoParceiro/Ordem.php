<?php

namespace App\Classes\IndicacaoParceiro;

use Order\Order;

class Ordem extends Order
{
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_MENSAGEM_INDICACAO_NOVO);
        $this->maisNovo();
        $this->maisVelho();
    }
}
