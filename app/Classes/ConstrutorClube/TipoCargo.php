<?php

namespace App\Classes\ConstrutorClube;

use Status\Status as StatusStatus;

class TipoCargo extends StatusStatus
{
    public const NORMAL = 'normal';
    public const PERSONALIZADO = 'personalizado';


    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::NORMAL       =>  'Normal',
            self::PERSONALIZADO => 'Cargos Personalizados',
        ]);
    }
}
