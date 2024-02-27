<?php

namespace App\Classes\Votacao\Pergunta;

use Status\Status;

final class Tipo extends Status
{
    public const UMA_ESCOLHA = 'uma_escolha';
    public const MULTIPLA_ESCOLHA = 'multipla_escolha';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::UMA_ESCOLHA      => 'Apenas uma escolha',
            self::MULTIPLA_ESCOLHA => 'Múltiplas escolhas',
        ]);
    }
}
