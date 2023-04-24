<?php

namespace App\Classes\UsuarioCliente;

use Status\Status;

final class TipoUsuario extends Status
{
    public const TITULAR = 'titular';
    public const DEPENDENTE = 'dependente';
    public const SUPER = 'super';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::TITULAR    => 'Titular',
            self::DEPENDENTE => 'Dependente',
            self::SUPER      => 'Super Usuário'
        ], numero: [1, 2, 3]);
    }
}
