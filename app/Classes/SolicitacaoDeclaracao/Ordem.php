<?php

namespace App\Classes\SolicitacaoDeclaracao;

use Order\Order;

class Ordem extends Order
{
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_SOLICITACAO_DECLARACAO);
        $this->maisNovo();
        $this->maisVelho();
    }
}
