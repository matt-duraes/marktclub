<?php

namespace App\Classes\SolicitacaoSalavip;

use Order\Order;

final class Ordem extends Order
{
    /**
     * @param string|null $valor
     */
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_SOLICITACAO_VOUCHER);
        $this->maisNovo();
        $this->maisVelho();
    }
}
