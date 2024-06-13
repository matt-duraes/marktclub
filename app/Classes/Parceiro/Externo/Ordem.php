<?php

namespace App\Classes\Parceiro\Externo;

use Order\Order;

final class Ordem extends Order
{
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_PARCEIRO_LOJA);
        $this->campo('nome-a-z', 'Nome A-Z', 'titulo', 'ASC');
        $this->campo('nome-z-a', 'Nome Z-A', 'titulo', 'DESC');
        $this->campo('data-asc', 'Mais velhos', 'data_criacao', 'ASC');
        $this->campo('data-desc', 'Mais novos', 'data_criacao', 'DESC');
    }
}
