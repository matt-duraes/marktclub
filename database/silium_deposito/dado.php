<?php

return [
    [
        'uuid'               => uuid(),
        'id_usuario_cliente' => 2,
        'nome_titular'       => null,
        'documento_cpf'      => null,
        'email'              => emailAleatorio(),
        'banco'              => null,
        'agencia'            => null,
        'conta'              => null,
        'tipo_conta'         => null,
        'pontuacao'          => null,
        'valor'              => null,
        'data_deposito'      => null,
        'documento_anexo'    => null,
        'tipo_operacao'      => 1,
        'tipo_resgate'       => 2,
        'status'             => 1
    ],
    [
        'uuid'               => uuid(),
        'id_usuario_cliente' => 1,
        'nome_titular'       => nomeCompletoAleatorio(),
        'documento_cpf'      => cpfAleatorio(),
        'email'              => emailAleatorio(),
        'banco'              => 'Bradesco',
        'agencia'            => '1646',
        'conta'              => '1316-5',
        'tipo_conta'         => 1,
        'pontuacao'          => 10000,
        'valor'              => null,
        'data_deposito'      => hoje(),
        'documento_anexo'    => null,
        'tipo_operacao'      => 2,
        'tipo_resgate'       => 1,
        'status'             => 1
    ],
    [
        'uuid'               => uuid(),
        'id_usuario_cliente' => 1,
        'nome_titular'       => nomeCompletoAleatorio(),
        'documento_cpf'      => cpfAleatorio(),
        'email'              => emailAleatorio(),
        'banco'              => 'Bradesco',
        'agencia'            => '1646',
        'conta'              => '1316-5',
        'tipo_conta'         => 1,
        'pontuacao'          => 10000,
        'valor'              => null,
        'data_deposito'      => hoje(),
        'documento_anexo'    => null,
        'tipo_operacao'      => 1,
        'tipo_resgate'       => 1,
        'status'             => 1
    ]
];
