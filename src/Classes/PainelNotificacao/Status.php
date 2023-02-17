<?php

namespace System\Classes\PainelNotificacao;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    const NOVO = 'novo';
    const VISUALIZADO = 'visualizado';
    const CLICADO = 'clicado';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::NOVO => 'Novo',
                self::VISUALIZADO => 'Visualizado',
                self::CLICADO => 'Clicado'
            ]
        );
    }
}
