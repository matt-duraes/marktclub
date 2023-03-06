<?php

namespace App\Classes\PublicacaoNoticia;

use Order\Order;


final class Ordem extends Order
{
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_PUBLICACAO_NOTICIA);
        $this->campo('publicacao-nova', 'Publicação mais nova', 'data_publicacao_inicio', 'DESC');
        $this->campo('publicacao-velha', 'Publicação mais velha', 'data_publicacao_inicio', 'ASC');
        $this->maisNovo();
        $this->maisVelho();
        $this->status();
    }
}
