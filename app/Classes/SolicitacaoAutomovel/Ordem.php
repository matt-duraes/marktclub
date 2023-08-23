<?php

namespace App\Classes\SolicitacaoAutomovel;

use Order\Order;

class Ordem extends Order
{
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_SOLICITACAO_AUTOMOVEL);
        $this->maisNovo();
        $this->maisVelho();
        $this->status();
    }
}
