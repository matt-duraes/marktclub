<?php

namespace App\Classes\Mensageria;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    const STATUS_NOVA = 'nova';
    const STATUS_ERRO = 'erro';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::STATUS_NOVA => 'Nova',
                self::STATUS_ERRO => 'Erro'
            ]
        );
    }
}
