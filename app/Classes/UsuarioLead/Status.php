<?php

namespace App\Classes\UsuarioLead;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                'novo' => 'Novo',
                'andamento' => 'Em andamento',
                'cadastro-realizado' => 'Cadastro realizado',
                'sem-interesse' => 'Sem interesse'
            ],
            cor: [
                'novo' => 'vermelho',
                'andamento' => 'azul',
                'cadastro-realizado' => 'verde',
                'sem-interesse' => 'marron'
            ],
        );
    }
}
