<?php

namespace App\Classes\StatusGeral;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    const STATUS_ATIVO = 'ativo';
    const STATUS_INATIVO = 'inativo';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::STATUS_ATIVO => 'Ativo',
                self::STATUS_INATIVO => 'Inativo',
            ],
            cor: [
                self::STATUS_ATIVO => 'verde',
                self::STATUS_INATIVO => 'vermelho',
            ],
        );
    }
}
