<?php

namespace App\Classes\Saude;

use Order\Order;

final class Ordem extends Order
{
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_SAUDE_CONTRATACAO);
        $this->maisNovo();
        $this->maisVelho();
        $this->status();
    }
}
