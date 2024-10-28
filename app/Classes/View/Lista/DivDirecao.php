<?php

namespace App\Classes\View\Lista;

use Status\Status as StatusStatus;

final class DivDirecao extends StatusStatus
{
    public const LINHA = 'linha';
    public const COLUNA = 'coluna';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::LINHA => 'Linha (row)',
            self::COLUNA => 'Coluna (column)',
        ]);
    }
}
