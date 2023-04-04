<?php

namespace App\Classes\ComercialEmpresa;

use Status\Status as StatusStatus;

final class FinalidadePrincipal extends StatusStatus
{
    public const PUBLICA = 'publica';
    public const PRIVADA = 'privada';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::PUBLICA => 'Entidade pública',
                self::PRIVADA => 'Empresa privada',
            ]
        );
    }
}
