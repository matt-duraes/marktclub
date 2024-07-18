<?php

namespace App\Classes\DemandaTarefa;

use Status\Status as StatusStatus;

final class Dificuldade extends StatusStatus
{
    public const MUITO_FACIL = 'muito-facil';
    public const FACIL = 'facil';
    public const NORMAL = 'normal';
    public const DIFICIL = 'dificil';
    public const MUITO_DIFICIL = 'muito-dificil';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::MUITO_FACIL   => 'Muito fácil',
            self::FACIL         => 'Fácil',
            self::NORMAL        => 'Normal',
            self::DIFICIL       => 'Difícil',
            self::MUITO_DIFICIL => 'Muito difícil'
        ]);
    }
}
