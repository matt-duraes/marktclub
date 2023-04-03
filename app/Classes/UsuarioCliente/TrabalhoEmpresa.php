<?php

namespace App\Classes\UsuarioCliente;

use Status\Status;

final class TrabalhoEmpresa extends Status
{
    public const EMPRESA = [
        'geral' => [
            'lista' => ['geral' => 'Geral'],
            'numero' => [2000]
        ],
        'marktclub' => [
            'lista' => ['marktclub' => 'Markt Club'],
            'numero' => [1000]
        ],
        'unareg' => [
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
        parent::__construct(empresa: self::EMPRESA);
    }
}
