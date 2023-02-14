<?php

namespace System\Classes\LogErro;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    const STATUS_NOVO = 'novo';
    const STATUS_CORRIGIDO = 'corrigido';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::STATUS_NOVO => 'Novo',
                self::STATUS_CORRIGIDO => 'Corrigido'
            ],
            cor: [
                self::STATUS_NOVO => 'vermelho',
                self::STATUS_CORRIGIDO => 'verde'
            ]
        );
    }
}
