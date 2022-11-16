<?php

namespace App\Classes\UsuarioCliente;

use Status\Status;

final class TipoUsuario extends Status
{
    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                'titular' => 'Titular',
                'dependente' => 'Dependente',
                'super' => 'Super Usuário'
            ],
            numero: [1, 2, 3]
        );
    }
}
