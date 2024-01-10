<?php

namespace App\Classes\Geral;

use Status\Status as StatusStatus;

final class Target extends StatusStatus
{
    public const SELF = '_self';
    public const BLANK = '_blank';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::SELF   => 'Mesma aba',
            self::BLANK  => 'Outra aba'
        ], [
            self::SELF   => 'verde',
            self::BLANK  => 'azul'
        ]);
    }
}
