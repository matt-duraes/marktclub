<?php

namespace System\Classes\PainelNotificacao;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                'novo' => 'Novo',
                'visualizado' => 'Visualizado',
                'clicado' => 'Clicado'
            ]
        );
    }
}
