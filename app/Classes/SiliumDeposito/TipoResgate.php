<?php

namespace App\Classes\SiliumDeposito;

use Status\Status;

class TipoResgate extends Status
{
    public const DINHEIRO = 'dinheiro';
    public const ANUIDADE = 'anuidade';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::DINHEIRO    => 'Dinheiro',
            self::ANUIDADE => 'Desconto na Anuidade'
        ]);
    }
}
