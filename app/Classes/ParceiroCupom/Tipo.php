<?php

namespace App\Classes\ParceiroCupom;

use Status\Status as StatusStatus;

final class Tipo extends StatusStatus
{
    public const LINK = 'link';
    public const CODIGO = 'codigo';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::LINK   => 'Link',
            self::CODIGO => 'Código'
        ]);
    }
}
