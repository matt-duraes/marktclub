<?php

namespace App\Classes\UsuarioLead;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    const NOVO = 'novo';
    const ANDAMENTO = 'andamento';
    const CADASTRO_REALIZADO = 'cadastro-realizado';
    const SEM_INTERESSE = 'sem-interesse';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::NOVO => 'Novo',
                self::ANDAMENTO => 'Em andamento',
                self::CADASTRO_REALIZADO => 'Cadastro realizado',
                self::ANDAMENTO => 'Sem interesse'
            ],
            cor: [
                self::NOVO => 'vermelho',
                self::ANDAMENTO => 'azul',
                self::CADASTRO_REALIZADO => 'verde',
                self::SEM_INTERESSE => 'marron'
            ],
        );
    }
}
