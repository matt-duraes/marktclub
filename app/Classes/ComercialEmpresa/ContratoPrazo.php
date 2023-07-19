<?php

namespace App\Classes\ComercialEmpresa;

use Status\Status as StatusStatus;

final class ContratoPrazo extends StatusStatus
{
    public const INDETERMINADO = 'indeterminado';
    public const DOZE_MESES = '12-meses';
    public const VINTE_QUADRO_MESES = '24-meses';
    public const TRINTA_SEIS_MESES = '36-meses';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::INDETERMINADO      => 'Indeterminado',
            self::DOZE_MESES         => '12 meses',
            self::VINTE_QUADRO_MESES => '24 meses',
            self::TRINTA_SEIS_MESES  => '36 meses'
        ]);
    }
}
