<?php

namespace App\Classes\Usuario\Ativar\Ciesc;

use Status\Status as StatusStatus;

class LocalTrabalho extends StatusStatus
{
    public const SENAI = 'SENAI';
    public const SESI = 'SESI';
    public const IEL = 'IEL';
    public const FIESC = 'FIESC';
    public const CIESC = 'CIESC';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::SENAI => 'SENAI',
            self::SESI  => 'SESI',
            self::IEL   => 'IEL',
            self::FIESC => 'FIESC',
            self::CIESC => 'CIESC'
        ]);
    }
}
