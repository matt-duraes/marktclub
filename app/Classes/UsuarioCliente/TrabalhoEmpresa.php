<?php

namespace App\Classes\UsuarioCliente;

use Status\Status;

final class TrabalhoEmpresa extends Status
{
    public const EMPRESA = [
        'geral'     => [
            'lista'  => [
                'geral' => 'Geral'
            ],
            'numero' => [2000]
        ],
        'marktclub' => [
            'lista'  => [
                'marktclub' => 'Youhuul'
            ],
            'numero' => [10000]
        ],
        'unareg'    => [
            'lista'  => [
                'anm'    => 'ANM',
                'ana'    => 'ANA',
                'anac'   => 'ANAC',
                'anatel' => 'ANATEL',
                'ancine' => 'ANCINE',
                'aneel'  => 'ANEEL',
                'anp'    => 'ANP',
                'ans'    => 'ANS',
                'antaq'  => 'ANTAQ',
                'antt'   => 'ANTT',
                'anvisa' => 'ANVISA'
            ],
            'numero' => [
                32396, 44205, 52201, 41231, 20224, 32200, 32300, 36208, 39251,
                39250, 36207
            ]
        ],
        'sinjutra'  => [
            'lista'  => [
                'empresa' => 'Empresa 1'
            ],
            'numero' => [3000]
        ],
    ];

    public function __construct(
        protected string|int|null $valor = null,
        bool $geral = false
    ) {
        parent::__construct(empresa: self::EMPRESA, geral: $geral);
    }
}
