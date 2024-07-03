<?php

use App\Classes\SiliumDeposito\TipoResgate;

return [
    [
        'uuid'                     => uuid(),
        'id_admin_empresa'         => 1,
        'desconto'                 => 0,
        'regra_conversao'          => ['acada' => 100, 'equivale' => 1],
        'pontuacao_minima_resgate' => [
            TipoResgate::DINHEIRO    => 10000,
            TipoResgate::MENSALIDADE => 1
        ],
        'validade_pontuacao'       => 12
    ],
    [
        'uuid'                     => uuid(),
        'id_admin_empresa'         => 2,
        'desconto'                 => 1,
        'regra_conversao'          => ['acada' => 100, 'equivale' => 1],
        'pontuacao_minima_resgate' => [
            TipoResgate::DINHEIRO    => 10000,
            TipoResgate::MENSALIDADE => 1
        ],
        'validade_pontuacao'       => 12
    ]
];
