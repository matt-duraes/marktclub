<?php

namespace App\Classes\SolicitacaoAutomovel;

use Order\Order;

final class Ordem extends Order
{
    /**
     * @param string|null $valor
     */
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_SOLICITACAO_AUTOMOVEL);
        $this->padrao('status');
        $this->status();
        $this->maisNovo();
        $this->maisVelho();
    }
}
