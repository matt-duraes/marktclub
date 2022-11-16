<?php

namespace App\Classes\UsuarioCliente;

use Status\Status;

final class Situacao extends Status
{
    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                'Ativo',
                'Aposentado',
                'Pensionista',
                'Cedido',
                'Excedente'
            ]
        );
    }
}
