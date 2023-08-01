<?php

namespace App\Classes\Saude;

use Status\Status;
use App\Classes\Saude\Interface\PlanoInteface;

class PlanoAmilSaoPaulo extends Status implements PlanoInteface
{
    public const PLANO_1 = 'plano-sp-1';
    public const PLANO_2 = 'plano-sp-2';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::PLANO_1 => 'Plano SP 1',
            self::PLANO_2 => 'Plano SP 2',
        ]);
    }
}
