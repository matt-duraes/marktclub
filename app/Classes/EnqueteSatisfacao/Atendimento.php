<?php

namespace App\Classes\EnqueteSatisfacao;

use Status\Status as StatusStatus;

final class Atendimento extends StatusStatus
{
    public const OTIMO = 'otimo';
    public const BOM = 'bom';
    public const RUIM = 'ruim';
    public const PESSIMO = 'pessimo';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::OTIMO      => 'Ótimo',
            self::BOM        => 'Bom',
            self::RUIM       => 'Ruim',
            self::PESSIMO    => 'Pessimo'
        ]);
    }
}
