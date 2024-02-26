<?php

namespace System\Classes\Contato;

use Status\Status;

class Local extends Status
{
    public const CLUBE = 'clube';
    public const PAINEL = 'painel';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::CLUBE  => 'Clube',
            self::PAINEL => 'Painel',
        ]);
    }
}
