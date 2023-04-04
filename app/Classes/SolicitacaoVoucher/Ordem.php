<?php

namespace App\Classes\SolicitacaoVoucher;

use Order\Order;

final class Ordem extends Order
{
    public function __construct(
        protected null|string $valor = null
    ) {
        $this->tabela(TABELA_SOLICITACAO_VOUCHER);
        $this->maisNovo();
        $this->maisVelho();
    }
}
