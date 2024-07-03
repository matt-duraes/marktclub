<?php

namespace App\Classes\SiliumComissao;

use Status\Status as StatusStatus;

class Status extends StatusStatus
{
    public const LIBERADO = 'liberado';
    public const NEGADO = 'negado';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::LIBERADO   => 'Liberado',
            self::NEGADO     => 'Negado'
        ], [
            self::LIBERADO   => 'verde',
            self::NEGADO     => 'vermelho'
        ]);
    }
}
