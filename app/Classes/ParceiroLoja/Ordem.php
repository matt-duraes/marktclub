<?php

namespace App\Classes\ParceiroLoja;

use Order\Order;

final class Ordem extends Order
{
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_PARCEIRO_LOJA);
        $this->campo('favorito', 'Seus favoritos', 'favorito', 'ASC');
        $this->campo('titulo-a-z', 'Título A-Z', 'titulo', 'ASC');
        $this->campo('titulo-z-a', 'Título Z-A', 'titulo', 'DESC');
        $this->maisNovo();
        $this->maisVelho();
        $this->campo('delivery', 'Lojas com delivery', 'delivery', 'ASC');
        $this->campo('nacional', 'Lojas nacionais', 'nacional', 'ASC');
    }
}
