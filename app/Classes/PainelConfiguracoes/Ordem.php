<?php

namespace App\Classes\PainelConfiguracoes;

use Order\Order;

final class Ordem extends Order
{
    /**
     * @param string|null $valor
     */
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_PAINEL_CONFIG);
        $this->padrao('data_criacao');
        $this->maisNovo();
        $this->maisVelho();
        $this->campo('nome-a-z', 'Título A-Z', 'titulo', 'ASC');
        $this->campo('nome-z-a', 'Título Z-A', 'titulo', 'DESC');
    }
}
