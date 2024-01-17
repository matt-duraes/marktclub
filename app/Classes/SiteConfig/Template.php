<?php

namespace App\Classes\SiteConfig;

use Status\Status as StatusStatus;

final class Template extends StatusStatus
{
    public const PADRAO = 'padrao';
    public const UNAREG = 'unareg';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::PADRAO => 'Padrão',
            self::UNAREG => 'UNAREG'
        ], [
            self::PADRAO => 'verde',
            self::UNAREG => 'azul'
        ]);
    }
}
