<?php

namespace App\Classes\IndicacaoParceiro;

use Status\Status as StatusStatus;

class Tipo extends StatusStatus
{
    public const CLUBE = 'clube';
    public const AUTOINDICACAO = 'autoindicacao';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::AUTOINDICACAO => 'autoindicacao',
            self::CLUBE         => 'clube'
        ]);
    }
}
