<?php

namespace App\Classes\UsuarioCliente;

use Status\Status;

final class TipoPagamento extends Status
{
    const EMPRESA = [
        'geral' => [
            'lista' => [
                'cartao-credito' => 'Cartão de Crédito',
                'cartao-debito' => 'Cartão de Débito',
                'boleto' => 'Boleto',
                'debito-conta' => 'Debito em Conta'
            ],
            'numero' => [6, 7, 8, 9]
        ],
        'unareg' => [
            'lista' => [
                'consignado-integral' => 'Consignado Integral',
                'consignado-parcial' => 'Consignado Parcial',
                'boleto' => 'Boleto',
                'debito-conta' => 'Débito em conta',
                'desfiliado' => 'Desfiliado',
            ],
            'numero' => [1, 2, 3, 4, 5]
        ]
    ];

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(empresa: self::EMPRESA);
    }
}
