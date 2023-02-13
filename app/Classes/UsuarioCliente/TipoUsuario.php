<?php

namespace App\Classes\UsuarioCliente;

use Status\Status;

final class TipoUsuario extends Status
{
    const TIPO_TITULAR = 'titular';
    const TIPO_DEPENDENTE = 'dependente';
    const TIPO_SUPER = 'super';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::TIPO_TITULAR => 'Titular',
                self::TIPO_DEPENDENTE => 'Dependente',
                self::TIPO_SUPER => 'Super Usuário'
            ],
            numero: [1, 2, 3]
        );
    }
}
