<?php

namespace App\Classes\IndicacaoNovoParceiro;

use Status\Status as StatusStatus;

class Status extends StatusStatus
{
    public const PENDENTE = 'pendente';
    public const VISUALIZADO = 'visualizado';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::PENDENTE    => 'Pendente',
            self::VISUALIZADO => 'Visualizado'
        ]);
    }
}
