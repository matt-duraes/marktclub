<?php

namespace App\Classes\AlbumDado;

use Order\Order;

class Ordem extends Order
{
    /**
     * @param string|null $valor
     */
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_ALBUM_DADO);
        $this->padrao('status');
        $this->campo('publicacao-nova', 'Publicação mais nova', 'data_inicio', 'DESC');
        $this->campo('publicacao-velha', 'Publicação mais velha', 'data_inicio', 'ASC');
        $this->maisNovo();
        $this->maisVelho();
        $this->status();
    }
}
