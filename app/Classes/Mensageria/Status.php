<?php

namespace App\Classes\Mensageria;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    const NOVA = 'nova';
    const ERRO = 'erro';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::NOVA => 'Nova',
                self::ERRO => 'Erro'
            ]
        );
    }
}
