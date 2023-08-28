<?php

namespace App\Classes\PublicacaoPagina;

use Order\Order;

final class Ordem extends Order
{
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_PUBLICACAO_PAGINA);
        $this->campo('publicacao-nova', 'Publicação mais nova', 'data_inicio', 'DESC');
        $this->campo('publicacao-velha', 'Publicação mais velha', 'data_inicio', 'ASC');
        $this->maisNovo();
        $this->maisVelho();
        $this->status();
    }
}
