<?php

namespace App\Classes\View\Lista;

use Status\Status as StatusStatus;

final class TextoAlinhamento extends StatusStatus
{
    public const ESQUERDA = 'esquerda';
    public const DIREITA = 'direita';
    public const CENTRO = 'centro';
    public const JUSTIFICADO = 'justificado';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::ESQUERDA  => 'Esquerda',
            self::DIREITA => 'Direita',
            self::CENTRO => 'Centralizado',
            self::JUSTIFICADO => 'Justificado',
        ]);
    }
}
