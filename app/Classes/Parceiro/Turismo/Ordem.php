<?php

namespace App\Classes\Parceiro\Turismo;

use Order\Order;

final class Ordem extends Order
{
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_PARCEIRO_TURISMO);
        $this->campo('status', 'Status', 'status', 'ASC');
        $this->campo('nome-a-z', 'Nome A-Z', 'titulo', 'ASC');
        $this->campo('nome-z-a', 'Nome Z-A', 'titulo', 'DESC');
        $this->maisNovo();
        $this->maisVelho();
    }
}
