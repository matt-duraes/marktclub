<?php

namespace App\Classes\SolicitacaoChequeBonus;

use Order\Order;

class Ordem extends Order
{
    /**
     * @param string|null $valor
     */
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_SOLICITACAO_CHEQUE_BONUS);
        $this->padrao('status');
        $this->status();
        $this->maisNovo();
        $this->maisVelho();
    }
}
