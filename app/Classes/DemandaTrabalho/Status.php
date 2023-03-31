<?php

namespace App\Classes\DemandaTrabalho;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public const TRABALHANDO = 'trabalhando';
    public const FINALIZADO = 'finalizado';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::TRABALHANDO => 'Trabalhando',
                self::FINALIZADO => 'Finalizado'
            ]
        );
    }
}
