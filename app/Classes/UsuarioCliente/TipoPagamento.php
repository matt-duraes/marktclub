<?php

namespace App\Classes\UsuarioCliente;

use Status\Status;

final class TipoPagamento extends Status
{
    private array $listaPorEmpresa = [
        0 => [
            'lista' => [
                'cartao-credito' => 'Cartão de Crédito',
                'cartao-debito' => 'Cartão de Débito',
                'boleto' => 'Boleto',
                'debito-conta' => 'Debito em Conta'
            ],
            'numero' => [6, 7, 8, 9]
        ],
        19 => [
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
        $idEmpresa = sessao('USUARIO.empresa', padrao: 0);
        $idEmpresa = array_key_exists($idEmpresa, $this->listaPorEmpresa) ? $idEmpresa : 0;

        parent::__construct(
            lista: $this->listaPorEmpresa[$idEmpresa]['lista'] ?? $this->listaPorEmpresa[0]['lista'],
            numero: $this->listaPorEmpresa[$idEmpresa]['numero'] ?? $this->listaPorEmpresa[0]['numero'],
        );
    }
}
