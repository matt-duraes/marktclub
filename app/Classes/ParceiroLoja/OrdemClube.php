<?php

namespace App\Classes\ParceiroLoja;

use Order\Order;

final class OrdemClube extends Order
{
    public function __construct(
        protected null|string $valor = null
    ) {
        $this->tabela(TABELA_PARCEIRO_LOJA);
        $this->campo('favorito', 'Seus favoritos', 'favorito', 'ASC');
        $this->campo('mais-novos', 'Lojas novas', 'novo', 'DESC');
        $this->campo('nome-a-z', 'Nome A-Z', 'nome', 'ASC');
        $this->campo('nome-z-a', 'Nome Z-A', 'nome', 'DESC');
    }
}
