<?php

namespace App\Classes\PainelHistorico;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct([
            'com-mensagem' => 'Com mensagem',
            'sem-mensagem' => 'Sem mensagem'
        ]);
    }
}
