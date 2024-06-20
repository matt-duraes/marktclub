<?php

return [
    [
        'uuid'               => uuid(),
        'id_usuario_cliente' => 1,
        'nome_titular'       => nomeAleatorio(),
        'documento_cpf'      => cpfAleatorio(),
        'email'              => emailAleatorio(),
        'banco'              => 'Bradesco',
        'agencia'            => '1646',
        'conta'              => '1316-5',
        'tipo_conta'         => 1,
        'pontuacao'          => 10000,
        'status'             => 1
    ],
    [
        'uuid'               => uuid(),
        'id_usuario_cliente' => 1,
        'nome_titular'       => nomeAleatorio(),
        'documento_cpf'      => cpfAleatorio(),
        'email'              => emailAleatorio(),
        'banco'              => 'Itáu',
        'agencia'            => '6431',
        'conta'              => '9781-5',
        'tipo_conta'         => 2,
        'pontuacao'          => 20000,
        'status'             => 2
    ],
    [
        'uuid'               => uuid(),
        'id_usuario_cliente' => 1,
        'nome_titular'       => nomeAleatorio(),
        'documento_cpf'      => cpfAleatorio(),
        'email'              => emailAleatorio(),
        'banco'              => 'Nubank',
        'agencia'            => '971200',
        'conta'              => '65554-8',
        'tipo_conta'         => 2,
        'pontuacao'          => 10000,
        'status'             => 2
    ],
    [
        'uuid'               => uuid(),
        'id_usuario_cliente' => 1,
        'nome_titular'       => nomeAleatorio(),
        'documento_cpf'      => cpfAleatorio(),
        'email'              => emailAleatorio(),
        'banco'              => 'Inter',
        'agencia'            => '00001',
        'conta'              => '99216-5',
        'tipo_conta'         => 2,
        'pontuacao'          => 20000,
        'status'             => 1
    ]
];
