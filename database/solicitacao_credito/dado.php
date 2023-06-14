<?php

return [
    [
        'uuid'           => uuid(),
        'codigo'         => uuid(),
        'usuario'        => 1,
        'empresa'        => 1,
        'operadora'      => 1,
        'tipo'           => 1,
        'valor'          => numeroAleatorio(1, 100000),
        'parcelas'       => numeroAleatorio(1, 96),
        'valor_parcelas' => numeroAleatorio(1, 100000),
        'observacao'     => '',
        'status'         => 1
    ],
    [
        'uuid'           => uuid(),
        'codigo'         => uuid(),
        'usuario'        => 1,
        'empresa'        => 1,
        'operadora'      => 1,
        'tipo'           => 3,
        'valor'          => numeroAleatorio(1, 100000),
        'parcelas'       => numeroAleatorio(1, 96),
        'valor_parcelas' => numeroAleatorio(1, 100000),
        'observacao'     => '',
        'status'         => 2
    ],
    [
        'uuid'           => uuid(),
        'codigo'         => uuid(),
        'usuario'        => 1,
        'empresa'        => 1,
        'operadora'      => 1,
        'tipo'           => 2,
        'valor'          => numeroAleatorio(1, 100000),
        'parcelas'       => numeroAleatorio(1, 96),
        'valor_parcelas' => numeroAleatorio(1, 100000),
        'observacao'     => '',
        'status'         => 3
    ]
];
