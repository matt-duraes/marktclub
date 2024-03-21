<?php

namespace App\Classes\Solicitacao\Link;

use Status\Status as StatusStatus;

class Status extends StatusStatus
{
    public const LIVRE = 'livre';
    public const EMITIDO = 'emitido';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::LIVRE   => 'Livre',
            self::EMITIDO => 'Emitido',
        ], [
            self::LIVRE   => 'verde',
            self::EMITIDO => 'azul',
        ]);
    }
}
