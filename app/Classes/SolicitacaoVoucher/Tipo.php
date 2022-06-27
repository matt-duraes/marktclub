<?php

namespace App\Classes\SolicitacaoVoucher;

use Status\Status;

final class Tipo extends Status
{
    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct([
            'voucher' => 'Voucher',
            'declaracao' => 'Declaração'
        ]);
    }
}
