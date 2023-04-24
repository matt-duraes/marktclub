<?php

namespace App\Classes\UsuarioCliente;

use Status\Status;

final class TipoPagamento extends Status
{
    public const EMPRESA = [
        'geral'  => [
            'lista'  => [
                'cartao-credito' => 'Cartão de Crédito',
                'cartao-debito'  => 'Cartão de Débito',
                'boleto'         => 'Boleto',
                'debito-conta'   => 'Debito em Conta'
            ],
            'numero' => [6, 7, 8, 9]
        ],
        'unareg' => [
            'lista'  => [
                'consignado-integral' => 'Consignado Integral',
                'consignado-parcial'  => 'Consignado Parcial',
                'boleto'              => 'Boleto',
                'debito-conta'        => 'Débito em conta',
                'desfiliado'          => 'Desfiliado',
                'deposito-bancario'   => 'Depósito Bancário'
            ],
            'numero' => [1, 2, 3, 4, 5, 10]
        ]
    ];

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct(empresa: self::EMPRESA);
    }
}
