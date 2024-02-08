<?php

namespace App\Classes\PublicacaoYoutube;

use Order\Order;

final class Ordem extends Order
{
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_PUBLICACAO_YOUTUBE);
        $this->campo('publicacao-nova', 'Publicação mais nova', 'data_inicio', 'DESC');
        $this->campo('publicacao-velha', 'Publicação mais velha', 'data_inicio', 'ASC');
        $this->status();
        $this->maisNovo();
        $this->maisVelho();
    }
}
