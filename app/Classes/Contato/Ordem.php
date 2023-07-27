<?php

namespace App\Classes\Contato;

use Order\Order;

class Ordem extends Order
{
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_MENSAGEM_CONTATO_NOVO);
        $this->maisNovo();
        $this->maisVelho();
    }
}
