<?php

namespace App\Classes\UsuarioCliente;

use Status\Status;

final class TrabalhoEmpresa extends Status
{
    private array $listaPorEmpresa = [
        0 => [
            'lista' => ['empresa-teste-01' => 'Empresa de Teste 01'],
            'numero' => [2000]
        ],
        1 => [
            'lista' => ['marktclub' => 'Markt Club'],
            'numero' => [1000]
        ],
        19 => [
            'lista' => [
                'anm' => 'ANM',
                'ana' => 'ANA',
                'anac' => 'ANAC',
                'anatel' => 'ANATEL',
                'ancine' => 'ANCINE',
                'aneel' => 'ANEEL',
                'anp' => 'ANP',
                'ans' => 'ANS',
                'antaq' => 'ANTAQ',
                'antt' => 'ANTT',
                'anvisa' => 'ANVISA',
            ],
            'numero' => [
                32396, 44205, 52201, 41231, 20224, 32200, 32300, 36208, 39251, 39250, 36207
            ]
        ]
    ];

    public function __construct(
        protected null|string|int $valor = null
    ) {
        $idEmpresa = defined('TOKEN') &&
            is_array(TOKEN) &&
            array_key_exists('empresa', TOKEN) &&
            array_key_exists(TOKEN['empresa']->get('id'), $this->listaPorEmpresa) ? TOKEN['empresa']->get('id') : 0;

        parent::__construct(
            lista: array_key_exists($idEmpresa, $this->listaPorEmpresa) ?
                $this->listaPorEmpresa[$idEmpresa]['lista'] : [],
            numero: array_key_exists($idEmpresa, $this->listaPorEmpresa) ?
                $this->listaPorEmpresa[$idEmpresa]['numero'] : []
        );
    }
}
