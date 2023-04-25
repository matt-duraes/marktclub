<?php

namespace App\Classes\CampanhaSorteio;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public const ATIVO = 'ativo';
    public const SORTEADO = 'sorteado';
    public const DELETADO = 'deletado';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::ATIVO    => 'Ativo',
            self::SORTEADO => 'Sorteado',
            self::DELETADO => 'Deletado'
        ]);
    }
}
