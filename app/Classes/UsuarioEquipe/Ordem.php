<?php

namespace App\Classes\UsuarioEquipe;

use Order\Order;


final class Ordem extends Order
{
    public function __construct(
        protected null|string $valor = null
    ) {
        $this->tabela(TABELA_USUARIO_EQUIPE);
        $this->status();
        $this->maisNovo();
        $this->maisVelho();
        $this->campo('nome-a-z', 'Nome A-Z', 'nome_real', 'ASC');
        $this->campo('nome-z-a', 'Nome Z-A', 'nome_real', 'DESC');
    }
}
