<?php

namespace App\Classes\CampanhaSorteio;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                'Ativo', 'Sorteado', 'Deletado'
            ]
        );
    }
}
