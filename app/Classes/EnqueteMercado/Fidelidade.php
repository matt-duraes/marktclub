<?php

namespace App\Classes\EnqueteMercado;

use Status\Status;

class Fidelidade extends Status
{
    public const DESCONTO = 'desconto';
    public const CASHBACK = 'cashback';
    public const CUPOM = 'cupom';

    /**
     * @param string|int|null $valor
     */
    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::DESCONTO => 'Descontos imediatos',
            self::CASHBACK => 'Cashback',
            self::CUPOM    => 'Cupom'
        ]);
    }
}
