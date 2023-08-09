<?php

namespace App\Classes\Saude;

use Status\Status;
use App\Classes\Saude\Interface\PlanoInteface;

class PlanoAmilBrasilia extends Status implements PlanoInteface
{
    public const PLANO_1 = 'amil_s80qc';
    public const PLANO_2 = 'amil_s80qp';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::PLANO_1 => 'Amil s80qc',
            self::PLANO_2 => 'Amil s80qp',
        ]);
    }
}
