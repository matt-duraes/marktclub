<?php

namespace App\Classes\ParceiroCupom;

use Order\Order;

final class Ordem extends Order
{
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_PARCEIRO_CUPOM);
        $this->padrao('status');
        $this->status();
        $this->campo('nome-a-z', 'Nome A-Z', 'nome', 'ASC');
        $this->campo('nome-z-a', 'Nome Z-A', 'nome', 'DESC');
        $this->maisNovo();
        $this->maisVelho();
    }
}
