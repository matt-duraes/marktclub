<?php

namespace System\Classes\LogErro;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    const NOVO = 'novo';
    const CORRIGIDO = 'corrigido';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::NOVO => 'Novo',
                self::CORRIGIDO => 'Corrigido'
            ],
            cor: [
                self::NOVO => 'vermelho',
                self::CORRIGIDO => 'verde'
            ]
        );
    }
}
