<?php

namespace App\Classes\SolicitacaoCredito;

use Order\Order;

final class Ordem extends Order
{
    /**
     * @param string|null $valor
     */
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_SOLICITACAO_CREDITO);
        $this->padrao('status');
        $this->campo('valor_total_maior', 'Total Maior', 'valor_total', 'DESC');
        $this->campo('valor_total_menor', 'Total Menor', 'valor_total', 'ASC');
        $this->status();
        $this->maisNovo();
        $this->maisVelho();
    }
}
