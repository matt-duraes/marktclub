<?php

namespace App\Classes\SolicitacaoAlfa;

use Status\Status;

class Tipo extends Status
{
    public const CREDITO = 'credito';
    public const PORTABILIDADE = 'portabilidade';
    public const VEICULO = 'veiculo';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::CREDITO       => 'Crédito',
            self::PORTABILIDADE => 'Portabilidade',
            self::VEICULO       => 'Veículo'
        ]);
    }
}
