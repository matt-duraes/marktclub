<?php

namespace App\Classes\UsuarioCliente;

use Status\Status;

final class SalvarAtualizar extends Status
{
    public const CADASTRAR_USUARIO = 'cadastrar-usuario';
    public const USUARIO_NOVO = 'usuario-novo';
    public const USUARIO_EXISTENTE = 'usuario-existente';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::CADASTRAR_USUARIO => 'Cadastrar Usuário',
            self::USUARIO_NOVO      => 'Usuário novo',
            self::USUARIO_EXISTENTE => 'Usuário existente'
        ]);
    }
}
