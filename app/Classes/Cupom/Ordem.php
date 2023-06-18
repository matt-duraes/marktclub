<?php

namespace App\Classes\Cupom;

use Order\Order;

final class Ordem extends Order
{
    public function __construct(
        protected ?string $valor = null
    ) {
        $this->maisNovo();
        $this->maisVelho();
    }
}
