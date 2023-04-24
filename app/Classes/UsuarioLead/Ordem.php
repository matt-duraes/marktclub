<?php

namespace App\Classes\UsuarioLead;

use Order\Order;

final class Ordem extends Order
{
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->tabela(TABELA_USUARIO_LEAD);
        $this->status();
        $this->maisNovo();
        $this->maisVelho();
        $this->campo('nome-a-z', 'Nome A-Z', 'nome_completo', 'ASC');
        $this->campo('nome-z-a', 'Nome Z-A', 'nome_completo', 'DESC');
    }
}
