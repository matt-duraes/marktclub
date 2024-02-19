<?php

namespace App\Classes\PublicacaoArquivo;

use Status\Status as StatusStatus;

class Tipo extends StatusStatus
{
    public const GERAL = 'geral';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::GERAL => 'Geral'
        ]);
    }
}
