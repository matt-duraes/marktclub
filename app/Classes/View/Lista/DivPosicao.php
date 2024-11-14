<?php

namespace App\Classes\View\Lista;

use Status\Status as StatusStatus;

final class DivPosicao extends StatusStatus
{
    public const ESQUERDA = 'esquerda';
    public const DIREITA = 'direita';
    public const CENTRO = 'centro';
    public const ENTRE = 'entre';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::ESQUERDA => 'Esquerda (flex-start)',
            self::DIREITA  => 'Direita (flex-end)',
            self::CENTRO   => 'Centro (center)',
            self::ENTRE    => 'Entre (between)',
        ]);
    }
}
