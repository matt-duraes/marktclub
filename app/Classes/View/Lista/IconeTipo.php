<?php

namespace App\Classes\View\Lista;

use Status\Status as StatusStatus;

final class IconeTipo extends StatusStatus
{
    public const NORMAL = 'normal';
    public const QUADRADO = 'quadrado';
    public const REDONTO = 'redondo';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::NORMAL   => 'Normal',
            self::QUADRADO => 'Quadrado',
            self::REDONTO  => 'Redondo',
        ]);
    }
}
