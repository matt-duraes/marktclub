<?php

namespace App\Classes\SolicitacaoLoja;

use Status\Status as StatusStatus;

class Origem extends StatusStatus
{
    public const CLUBE = 'clube';

    /**
     * @param string|int|null $valor
     */
    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::CLUBE => 'Clube'
        ]);
    }
}
