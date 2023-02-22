<?php

namespace App\Classes\DemandaTarefa;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    const AGUARDANDO = 'aguardando';
    const ANDAMENTO = 'andamento';
    const CONCLIDA = 'concluida';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::AGUARDANDO => 'Aguardando',
                self::ANDAMENTO => 'Em andamento',
                self::CONCLIDA => 'Concluida'
            ]
        );
    }
}
