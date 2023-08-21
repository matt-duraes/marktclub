<?php

namespace App\Classes\ComunicacaoPublicidade;

use Order\Order;

final class Ordem extends Order
{
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_COMUNICACAO_PUBLICIDADE);
        $this->status();
        $this->maisNovo();
        $this->maisVelho();
        $this->campo('tipo', 'Tipo', 'tipo', 'ASC');
        $this->campo('titulo-a-z', 'Título A-Z', 'titulo', 'ASC');
        $this->campo('titulo-z-a', 'Título Z-A', 'titulo', 'DESC');
    }
}
