<?php

namespace App\Classes\ParceiroConvenio;

use Order\Order;


final class Ordem extends Order
{
    public function __construct(
        protected null|string $valor = null
    ) {
        $this->tabela(TABELA_PARCEIRO_NOVO);
        $this->campo('titulo-a-z', 'Títiulo A-Z', 'titulo', 'ASC');
        $this->campo('titulo-z-a', 'Títiulo Z-A', 'titulo', 'DESC');
        $this->rand();
        $this->maisNovo();
        $this->maisVelho();
    }
}
