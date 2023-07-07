<?php

namespace App\Classes\ParceiroLoja;

use Order\Order;

final class Ordem extends Order
{
    public const FAVORITO = 'favorito';

    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_PARCEIRO_LOJA);
        $this->rand();
        $this->campo('favorito', 'Seus favoritos', 'id_parceiro_loja', 'DESC', tabela: TABELA_PARCEIRO_FAVORITO);
        $this->campo('titulo-a-z', 'Título A-Z', 'titulo', 'ASC');
        $this->campo('titulo-z-a', 'Título Z-A', 'titulo', 'DESC');
        $this->maisNovo();
        $this->maisVelho();
        $this->campo('delivery', 'Lojas com delivery', 'delivery', 'ASC');
        $this->campo('nacional', 'Lojas nacionais', 'nacional', 'ASC');
    }
}
