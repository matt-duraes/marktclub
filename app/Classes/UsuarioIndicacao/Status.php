<?php

namespace App\Classes\UsuarioIndicacao;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                'indicado' => 'Indicado',
                'ativado' => 'Ativado',
                'bloqueado' => 'Bloqueado'
            ],
            cor: [
                'indicado' => 'azul',
                'ativado' => 'verde',
                'bloqueado' => 'vermelho'
            ],
        );
    }
}
