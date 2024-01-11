<?php

namespace App\Classes\PainelConfiguracoes;

use Order\Order;

final class Ordem extends Order
{
    /**
     * @param string|null $valor
     */
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_PAINEL_CONFIG);
        $this->padrao('data_criacao');
        $this->maisNovo();
        $this->maisVelho();
    }
}
