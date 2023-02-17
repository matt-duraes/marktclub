<?php

namespace System\Classes\PainelHistorico;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    const COM_MENSAGEM = 'com-mensagem';
    const SEM_MENSAGEM = 'sem-mensagem';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct([
            self::COM_MENSAGEM => 'Com mensagem',
            self::SEM_MENSAGEM => 'Sem mensagem'
        ]);
    }
}
