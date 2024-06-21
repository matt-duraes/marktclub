<?php

return [
    [
        'uuid'               => uuid(),
        'id_usuario_cliente' => 1,
        'parceiro'           => 'Techlab',
        'valor_compra'       => number_format(numeroAleatorio(), 2, thousands_separator: ''),
        'comissao_usuario'   => number_format(numeroAleatorio(), 2, thousands_separator: ''),
        'pontuacao'          => numeroAleatorio(),
        'data_compra'        => dataPassadaAleatorio(),
        'status'             => 1
    ],
    [
        'uuid'               => uuid(),
        'id_usuario_cliente' => 1,
        'parceiro'           => 'Kabum',
        'valor_compra'       => number_format(numeroAleatorio(), 2, thousands_separator: ''),
        'comissao_usuario'   => number_format(numeroAleatorio(), 2, thousands_separator: ''),
        'pontuacao'          => numeroAleatorio(),
        'data_compra'        => dataPassadaAleatorio(),
        'status'             => 1
    ]
];
