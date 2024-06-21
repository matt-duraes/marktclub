<?php

return [
    [
        'uuid'               => uuid(),
        'id_usuario_cliente' => 1,
        'saldo_silium'       => 10000,
        'data_validade'      => dataFuturaAleatorio()
    ],
    [
        'uuid'               => uuid(),
        'id_usuario_cliente' => 2,
        'saldo_silium'       => 20000,
        'data_validade'      => dataFuturaAleatorio()
    ]
];
