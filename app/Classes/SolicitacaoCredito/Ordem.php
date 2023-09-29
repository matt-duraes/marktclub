<?php

namespace App\Classes\SolicitacaoCredito;

use Order\Order;

class Ordem extends Order
{
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_SOLICITACAO_CREDITO);
        $this->maisNovo();
        $this->maisVelho();
        $this->status();
    }
}
