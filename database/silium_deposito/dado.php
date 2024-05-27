<?php

return [
    [
        'uuid'               => uuid(),
        'id_admin_empresa'   => 1,
        'id_usuario_cliente' => 1,
        'nome_titular'       => nomeAleatorio(),
        'documento_cpf'      => cpfAleatorio(),
        'banco'              => 'Bradesco',
        'agencia'            => '1646',
        'conta'              => '1316-5',
        'tipo_conta'         => 1,
        'valor'              => numeroAleatorio(),
        'pontuacao'          => 10000,
        'data_deposito'      => hoje(),
        'documento_anexo'    => null,
        'status'             => 1
    ],
    [
        'uuid'               => uuid(),
        'id_admin_empresa'   => 1,
        'id_usuario_cliente' => 1,
        'nome_titular'       => nomeAleatorio(),
        'documento_cpf'      => cpfAleatorio(),
        'banco'              => 'Itáu',
        'agencia'            => '6431',
        'conta'              => '9781-5',
        'tipo_conta'         => 2,
        'valor'              => numeroAleatorio(),
        'pontuacao'          => 20000,
        'data_deposito'      => hoje(),
        'documento_anexo'    => null,
        'status'             => 2
    ],
    [
        'uuid'               => uuid(),
        'id_admin_empresa'   => 1,
        'id_usuario_cliente' => 1,
        'nome_titular'       => nomeAleatorio(),
        'documento_cpf'      => cpfAleatorio(),
        'banco'              => 'Nubank',
        'agencia'            => '971200',
        'conta'              => '65554-8',
        'tipo_conta'         => 2,
        'valor'              => numeroAleatorio(),
        'pontuacao'          => 10000,
        'data_deposito'      => hoje(),
        'documento_anexo'    => null,
        'status'             => 3
    ],
    [
        'uuid'               => uuid(),
        'id_admin_empresa'   => 1,
        'id_usuario_cliente' => 1,
        'nome_titular'       => nomeAleatorio(),
        'documento_cpf'      => cpfAleatorio(),
        'banco'              => 'Inter',
        'agencia'            => '00001',
        'conta'              => '99216-5',
        'tipo_conta'         => 2,
        'valor'              => numeroAleatorio(),
        'pontuacao'          => 20000,
        'data_deposito'      => hoje(),
        'documento_anexo'    => null,
        'status'             => 4
    ]
];
