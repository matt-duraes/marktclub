<?php

namespace App\Classes\SolicitacaoVoucher;

use Status\Status;

final class Tipo extends Status
{
    const TIPO_VOUCHER = 'voucher';
    const TIPO_DECLARACAO = 'declaracao';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct([
            self::TIPO_VOUCHER => 'Voucher',
            self::TIPO_DECLARACAO => 'Declaração'
        ]);
    }
}
