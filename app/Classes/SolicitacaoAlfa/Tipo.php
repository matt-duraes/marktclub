<?php

namespace App\Classes\SolicitacaoAlfa;

use Status\Status;

class Tipo extends Status
{
    public const CONSIGNADO = 'consignado';
    public const CREDITO_PESSOAL = 'credito_pessoal';
    public const VEICULO_NOVO = 'veiculo_novo';
    public const VEICULO_SEMINOVO = 'veiculo_sminovo';
    public const PORTABILIDADE = 'portabilidade';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::CONSIGNADO       => 'Consignado',
            self::CREDITO_PESSOAL  => 'Crédito pessoal',
            self::VEICULO_NOVO     => 'Veículo 0Km',
            self::VEICULO_SEMINOVO => 'Veículo Seminovo',
            self::PORTABILIDADE    => 'Portabilidade'
        ]);
    }
}
