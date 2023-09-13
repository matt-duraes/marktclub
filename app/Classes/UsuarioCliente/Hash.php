<?php

namespace App\Classes\UsuarioCliente;

use Status\Status;

final class Hash extends Status
{
    public const ATIVAR = 'ativar';
    public const RECUPERAR_SENHA = 'recuperar_senha';
    public const LOGIN = 'login';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::ATIVAR          => 'Ativar',
            self::RECUPERAR_SENHA => 'Recuperar senha',
            self::LOGIN           => 'Login'
        ]);
    }
}
