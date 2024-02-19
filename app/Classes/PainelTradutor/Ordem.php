<?php

namespace App\Classes\PainelTradutor;

use Order\Order;

class Ordem extends Order
{
    /**
     * @param string|null $valor
     */
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_PAINEL_TRADUTOR);
        $this->status();
        $this->maisNovo();
        $this->maisVelho();
        $this->campo('nome-a-z', 'Nome A-Z', 'nome', 'ASC');
        $this->campo('nome-z-a', 'Nome Z-A', 'nome', 'DESC');
    }
}
