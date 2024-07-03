<?php

namespace App\Classes\SiliumDeposito;

use Status\Status;

class TipoResgate extends Status
{
    public const DINHEIRO = 'dinheiro';
    public const MENSALIDADE = 'mensalidade';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::DINHEIRO    => 'Dinheiro',
            self::MENSALIDADE => 'Desconto na Mensalidade'
        ]);
    }
}
