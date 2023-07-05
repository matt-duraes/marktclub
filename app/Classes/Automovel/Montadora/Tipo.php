<?php

namespace App\Classes\Automovel\Montadora;

use Status\Status;

final class Tipo extends Status
{
    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct([
            'automovel' => 'Automóvel',
            'parceiro' => 'Parceiro',
            'moto' => 'Moto',
            'Aluguel' => 'Aluguel',
            'montadora_vinculo' => 'Montadora para vínculo'
        ]);
    }
}
