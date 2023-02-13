<?php

namespace App\Classes\UsuarioLead;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    const STATUS_NOVO = 'novo';
    const STATUS_ANDAMENTO = 'andamento';
    const STATUS_CADASTRO_REALIZADO = 'cadastro-realizado';
    const STATUS_SEM_INTERESSE = 'sem-interesse';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::STATUS_NOVO => 'Novo',
                self::STATUS_ANDAMENTO => 'Em andamento',
                self::STATUS_CADASTRO_REALIZADO => 'Cadastro realizado',
                self::STATUS_ANDAMENTO => 'Sem interesse'
            ],
            cor: [
                self::STATUS_NOVO => 'vermelho',
                self::STATUS_ANDAMENTO => 'azul',
                self::STATUS_CADASTRO_REALIZADO => 'verde',
                self::STATUS_SEM_INTERESSE => 'marron'
            ],
        );
    }
}
