<?php

namespace System\Classes\LogErro;

use Order\Order;

class Ordem extends Order
{
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_LOG_ERRO);
        $this->padrao('data_atualizacao');
        $this->maisNovo();
        $this->maisVelho();
    }
}