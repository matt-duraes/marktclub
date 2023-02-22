<?php

namespace App\Classes\CampanhaSorteio;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    const ATIVO = '';
    const SORTEADO = '';
    const DELETADO = '';
    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::ATIVO => 'Ativo',
                self::SORTEADO => 'Sorteado',
                self::DELETADO => 'Deletado'
            ]
        );
    }
}
