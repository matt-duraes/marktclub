<?php

namespace App\Classes\EnqueteSatisfacao;

use Status\Status as StatusStatus;

final class Atendimento extends StatusStatus
{
    public const EXCELENTE = 'excelente';
    public const OTIMO = 'otimo';
    public const BOM = 'bom';
    public const RUIM = 'ruim';
    public const PESSIMO = 'pessimo';

    /**
     * @param string|int|null $valor
     */
    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::EXCELENTE => 'Excelente',
            self::OTIMO     => 'Ótimo',
            self::BOM       => 'Bom',
            self::RUIM      => 'Ruim',
            self::PESSIMO   => 'Pessimo'
        ]);
    }
}
