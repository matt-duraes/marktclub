<?php

namespace App\Classes\ApiApp;

use Order\Order;

final class Ordem extends Order
{
    public function __construct(
        protected null|string $valor = null
    ) {
        $this->tabela(TABELA_AUTH_APP);
        $this->status();
        $this->maisNovo();
        $this->maisVelho();
        $this->campo('nome-a-z', 'Nome A-Z', 'nome', 'ASC');
        $this->campo('nome-z-a', 'Nome Z-A', 'nome', 'DESC');
    }
}
