<?php

namespace App\Classes\AlbumDado;

use Order\Order;

final class Ordem extends Order
{
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_ALBUM_DADO);
        $this->campo('publicacao-nova', 'Publicação mais nova', 'data_inicio', 'DESC');
        $this->campo('publicacao-velha', 'Publicação mais velha', 'data_inicio', 'ASC');
        $this->maisNovo();
        $this->maisVelho();
        $this->status();
    }
}
