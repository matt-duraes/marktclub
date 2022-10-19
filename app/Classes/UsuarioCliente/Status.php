<?php

namespace App\Classes\UsuarioCliente;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                'ativo' => 'Ativo',
                'inativo' => 'Inativo',
                'bloqueado' => 'Bloqueado',
                'indicacao' => 'Indicação'
            ],
            cor: [
                'ativo' => 'verde',
                'inativo' => 'azul',
                'bloqueado' => 'vermelho',
                'indicacao' => 'marrom'
            ],
            numero: [1, 2, 3, 5]
        );
    }
}
