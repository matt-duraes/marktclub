<?php

namespace App\Classes\SolicitacaoChequeBonus;

use Order\Order;

class Ordem extends Order
{
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_SOLICITACAO_CHEQUE_BONUS);
        $this->maisNovo();
        $this->maisVelho();
        $this->status();
    }
}
