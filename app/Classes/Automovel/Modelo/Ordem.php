<?php

namespace App\Classes\Automovel\Modelo;

use Order\Order;

final class Ordem extends Order
{
    /**
     * @param string|null $valor
     */
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_AUTOMOVEL_MODELO);
        $this->padrao('status');
        $this->campo('expirando', 'Expirando', 'data_final', 'ASC');
        $this->status();
        $this->maisNovo();
        $this->maisVelho();
        $this->campo('nome-a-z', 'Título A-Z', 'titulo', 'ASC');
        $this->campo('nome-z-a', 'Título Z-A', 'titulo', 'DESC');
    }
}
