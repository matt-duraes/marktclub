<?php

namespace App\Classes\Saude;

use Status\Status;

class Operadora extends Status
{
    public const AMIL                    = 'amil';
    public const CENTRAL_NACIONAL_UNIMED = 'central_nacional_unimed';
    public const CNU_FLORIANOPIS         = 'cnu_florianopolis';
    public const UNIMED                  = 'unimed';
    public const UNIMED_SEGURO           = 'unimed_seguros';
    public const UNIMED_NATAL            = 'unimed_natal';
    public const UNIMED_JUNDIAI          = 'unimed_jundiai';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::UNIMED                  => 'Unimed Vitória',
            self::UNIMED_SEGURO           => 'Unimed Seguro',
            self::CENTRAL_NACIONAL_UNIMED => 'Central Nacional Unimed',
            self::CNU_FLORIANOPIS         => 'Central Nacional Unimed Florianópolis',
            self::AMIL                    => 'Amil',
            self::UNIMED_NATAL            => 'Unimed Natal',
            self::UNIMED_JUNDIAI          => 'Unimed Jundiaí',
        ]);
    }
}
