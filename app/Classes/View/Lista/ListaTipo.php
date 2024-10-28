<?php

namespace App\Classes\View\Lista;

use Status\Status as StatusStatus;

final class ListaTipo extends StatusStatus
{
    public const NUMERO = 'numero';
    public const BOLA = 'bola';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::NUMERO => 'Número',
            self::BOLA   => 'Bola',
        ]);
    }
}
