<?php

namespace App\Classes\Saude;

use Status\Status;

class Operadora extends Status
{
    public const AMIL = 'amil';
    public const CENTRAL_NACIONAL_UNIMED = 'central_nacional_unimed';
    public const CNU_FLORIANOPIS = 'cnu_florianopolis';
    public const UNIMED = 'unimed';
    public const UNIMED_SEGURO = 'unimed_seguros';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::UNIMED                  => 'Unimed',
            self::UNIMED_SEGURO           => 'Unimed Seguro',
            self::CENTRAL_NACIONAL_UNIMED => 'Central Nacional Unimed',
            self::CNU_FLORIANOPIS         => 'Central Nacional Unimed Florianópolis',
            self::AMIL                    => 'Amil'
        ]);
    }
}
