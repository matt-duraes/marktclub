<?php

namespace App\Classes\Automovel\Automovel;

use Status\Status as StatusStatus;

final class TipoProcedimento extends StatusStatus
{
    public const DECLARACAO = 'declaracao';
    public const VOUCHER = 'voucher';
    public const CARTABONUS = 'carta-bonus';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::DECLARACAO => 'Declaração',
            self::VOUCHER    => 'Voucher',
            self::CARTABONUS => 'Carta Bônus',
        ]);
    }
}
