<?php

namespace App\Classes\SolicitacaoLoja;

use Order\Order;

final class Ordem extends Order
{
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_SOLICITACAO_LOJA);
        $this->maisNovo();
        $this->maisVelho();
        $this->campo('status', 'Status', 'status', 'ASC');
    }
}
