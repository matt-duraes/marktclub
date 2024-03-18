<?php

namespace System\Classes\Endereco;

use Order\Order;

class Ordem extends Order
{
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_SISTEMA_ENDERECO);
        $this->maisNovo();
        $this->maisVelho();
        $this->campo('estado', 'Estado', 'estado', 'ASC');
        $this->campo('cidade', 'Cidade', 'cidade', 'ASC');
        $this->campo('principal', 'Principal', 'principal', 'DESC');
    }
}
