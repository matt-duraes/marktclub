<?php

namespace App\Classes\ComercialEmpresa;

use Status\Status as StatusStatus;

final class ContratoRenovacao extends StatusStatus
{
    public const AUTOMATICO = 'automatico';
    public const ADITIVO = 'aditivo';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::AUTOMATICO => 'Automático',
            self::ADITIVO    => 'Aditivo'
        ]);
    }
}
