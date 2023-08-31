<?php

namespace App\Classes\TextoClube;

use Order\Order;

final class Ordem extends Order
{
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_TEXTO_CLUBE);
        $this->padrao('ordem');

        $this->status();
        $this->maisNovo();
        $this->maisVelho();
        $this->campo('ordem', 'Ordem', 'ordem', 'ASC');
    }
}
