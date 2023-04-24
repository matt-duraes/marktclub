<?php

namespace App\Classes\ApiToken;

use Status\Status as StatusStatus;

final class Tipo extends StatusStatus
{
    public const CLUBE = 'clube';
    public const PAINEL = 'painel';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::CLUBE  => 'Clube',
            self::PAINEL => 'Painel'
        ], [
            self::CLUBE  => 'verde',
            self::PAINEL => 'azul'
        ]);
    }
}
