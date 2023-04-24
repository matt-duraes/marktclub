<?php

namespace App\Classes\UsuarioLead;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public const NOVO = 'novo';
    public const ANDAMENTO = 'andamento';
    public const CADASTRO_REALIZADO = 'cadastro-realizado';
    public const SEM_INTERESSE = 'sem-interesse';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::NOVO               => 'Novo',
            self::ANDAMENTO          => 'Em andamento',
            self::CADASTRO_REALIZADO => 'Cadastro realizado',
            self::SEM_INTERESSE      => 'Sem interesse'
        ], [
            self::NOVO               => 'vermelho',
            self::ANDAMENTO          => 'azul',
            self::CADASTRO_REALIZADO => 'verde',
            self::SEM_INTERESSE      => 'marron'
        ]);
    }
}
