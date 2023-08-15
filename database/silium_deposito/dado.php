<?php

return [
    [
        'uuid'             => uuid(),
        'id_admin_empresa' => 1,
        'id_usuario'       => 1,
        'nome'             => nomeAleatorio(),
        'documento_cpf'    => cpfAleatorio(),
        'banco'            => 'Bradesco',
        'agencia'          => '1646',
        'conta'            => '1316-5',
        'tipo_conta'       => 1,
        'comissao'         => 1,
        'valor'            => numeroAleatorio(),
        'data_deposito'    => dataFuturaAleatorio(),
        'status'           => 1
    ],
    [
        'uuid'             => uuid(),
        'id_admin_empresa' => 1,
        'id_usuario'       => 5,
        'nome'             => nomeAleatorio(),
        'documento_cpf'    => cpfAleatorio(),
        'banco'            => 'Caixa',
        'agencia'          => '1646',
        'conta'            => '1316-5',
        'tipo_conta'       => 1,
        'comissao'         => 1,
        'valor'            => numeroAleatorio(),
        'data_deposito'    => dataFuturaAleatorio(),
        'status'           => 1
    ]
];
