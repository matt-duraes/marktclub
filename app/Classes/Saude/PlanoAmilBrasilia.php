<?php

namespace App\Classes\Saude;

use Status\Status;
use App\Classes\Saude\Interface\PlanoInteface;

class PlanoAmilBrasilia extends Status implements PlanoInteface
{
    public const PLANO_1 = 'plano-bsb-1';
    public const PLANO_2 = 'plano-bsb-2';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::PLANO_1 => 'Plano BSB 1',
            self::PLANO_2 => 'Plano BSB 2',
        ]);
    }
}
