<?php

namespace System\Classes\Contato;

use Order\Order;

class Ordem extends Order
{
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_SISTEMA_CONTATO);
        $this->maisNovo();
        $this->maisVelho();
        $this->campo('principal', 'Principal', 'principal', 'DESC');
    }
}
