<?php

use App\Classes\SiliumDeposito\TipoResgate;

return [
    [
        'uuid'                     => uuid(),
        'regra_conversao'          => ['acada' => 100, 'equivale' => 1],
        'pontuacao_minima_resgate' => [
            TipoResgate::DINHEIRO    => 10000,
            TipoResgate::MENSALIDADE => 1
        ],
        'validade_pontuacao'       => 12
    ]
];
