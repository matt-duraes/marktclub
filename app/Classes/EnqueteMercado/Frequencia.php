<?php

namespace App\Classes\EnqueteMercado;

use Status\Status;

class Frequencia extends Status
{
    public const NUNCA = 'nunca';
    public const NAO_VALE = 'nao_vale';
    public const COMPREI = 'comprei';

    /**
     * @param string|int|null $valor
     */
    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::NUNCA    => 'Nunca comprei nada',
            self::NAO_VALE => 'Não vale a pena',
            self::COMPREI  => 'Já comprei e nunca conferi'
        ]);
    }
}
