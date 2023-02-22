<?php

namespace App\Classes\SolicitacaoVoucher;

use Status\Status;

final class Tipo extends Status
{
    const VOUCHER = 'voucher';
    const DECLARACAO = 'declaracao';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct([
            self::VOUCHER => 'Voucher',
            self::DECLARACAO => 'Declaração'
        ]);
    }
}
