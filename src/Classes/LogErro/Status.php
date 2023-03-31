<?php

namespace System\Classes\LogErro;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public const NOVO = 'novo';
    public const CORRIGIDO = 'corrigido';

    /**
     * @param  string|int|null  $valor
     */
    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            [
                self::NOVO => 'Novo',
                self::CORRIGIDO => 'Corrigido'
            ],
            [
                self::NOVO => 'vermelho',
                self::CORRIGIDO => 'verde'
            ]
        );
    }
}
