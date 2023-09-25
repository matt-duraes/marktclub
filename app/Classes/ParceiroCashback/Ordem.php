<?php

namespace App\Classes\ParceiroCashback;

use Order\Order;

final class Ordem extends Order
{
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_PARCEIRO_CASHBACK);
        $this->campo('nome-a-z', 'Nome A-Z', 'titulo', 'ASC');
        $this->campo('nome-z-a', 'Nome Z-A', 'titulo', 'DESC');
    }
}
