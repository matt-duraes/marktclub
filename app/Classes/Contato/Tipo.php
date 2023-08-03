<?php

namespace App\Classes\Contato;

use Status\Status as StatusStatus;

class Tipo extends StatusStatus
{
    public const AUTENTICADO = 'logado';
    public const SEM_AUTENTICACAO = 'sem_login';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::AUTENTICADO       => 'Logado',
            self::SEM_AUTENTICACAO    => 'Sem login',
        ]);
    }
}
