<?php

namespace App\Classes\AdminEmpresa;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    const STATUS_ATIVO = 'ativo';
    const STATUS_INATIVO = 'inativo';
    const STATUS_PROSPECCAO = 'prospeccao';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::STATUS_ATIVO => 'Ativo',
                self::STATUS_INATIVO => 'Inativo',
                self::STATUS_PROSPECCAO => 'Em prospecção'
            ]
        );
    }
}
