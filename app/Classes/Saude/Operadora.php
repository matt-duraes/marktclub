<?php

namespace App\Classes\Saude;

use Status\Status;

class Operadora extends Status
{
    public const AMIL = 'amil';
    public const CENTRAL_NACIONAL_UNIMED = 'central_nacional_unimed';
    public const CENTRAL_NACIONAL_UNIMED_FLORIPA = 'central_nacional_unimed_floripa';
    public const UNIMED = 'unimed';
    public const UNIMED_SEGURO = 'unimed_seguro';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::UNIMED                          => 'Unimed',
            self::UNIMED_SEGURO                   => 'Unimed Seguro',
            self::CENTRAL_NACIONAL_UNIMED         => 'Central Nacional Unimed',
            self::CENTRAL_NACIONAL_UNIMED_FLORIPA => 'Central Nacional Unimed Florianópolis',
            self::AMIL                            => 'Amil'
        ]);
    }
}
