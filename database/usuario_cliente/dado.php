<?php

return [
    [
        'id'                => 1,
        'cod'               => '5595203c-f7b1-4211-9981-bf09eb236b35',
        'empresa'           => 1,
        'tipo'              => 1,
        'nome'              => nomeCompletoAleatorio(),
        'documento'         => cpfAleatorio(),
        'email_pessoal'     => emailAleatorio(),
        'salt'              => password('Teste@1324'),
        'status'            => 1
    ],
    [
        'id'                => 2,
        'cod'               => '87cd8f94-601e-4e8e-b800-7f42a75fc0e1',
        'empresa'           => 1,
        'tipo'              => 1,
        'nome'              => nomeCompletoAleatorio(),
        'email_pessoal'     => emailAleatorio(),
        'documento'         => cpfAleatorio(),
        'salt'              => password('Teste@1324'),
        'status'            => 1
    ],
    [
        'id'                => 3,
        'cod'               => 'c91d0f54-d166-456e-9f21-e072722faa34',
        'empresa'           => 1,
        'tipo'              => 1,
        'nome'              => nomeCompletoAleatorio(),
        'documento'         => cpfAleatorio(),
        'email_pessoal'     => emailAleatorio(),
        'salt'              => password('Teste@1324'),
        'status'            => 1
    ],
    [
        'cod'               => uuid(),
        'empresa'           => 2,
        'tipo'              => 1,
        'nome'              => 'Usuario Empresa 2 = 1',
        'documento'         => cpfAleatorio(),
        'email_pessoal'     => emailAleatorio(),
        'salt'              => password('Teste@1324'),
        'status'            => 1
    ],
    [
        'id'                => 1126,
        'cod'               => 'cdc41730-abc7-4b51-abd7-698e2cb3c0b2',
        'empresa'           => 1,
        'tipo'              => 1,
        'nome'              => 'Usuário de Teste',
        'documento'         => '01234567890',
        'email_pessoal'     => 'teste@markt.club',
        'salt'              => password('Teste@1324'),
        'status'            => 1
    ],
    [
        'cod'               => uuid(),
        'empresa'           => 2,
        'tipo'              => 1,
        'nome'              => 'Usuario Empresa 2 = 2',
        'documento'         => cpfAleatorio(),
        'email_pessoal'     => emailAleatorio(),
        'salt'              => password('Teste@1324'),
        'status'            => 1
    ],
    [
        'cod'               => uuid(),
        'empresa'           => 2,
        'tipo'              => 1,
        'nome'              => 'Usuario Empresa 2 = 3',
        'documento'         => cpfAleatorio(),
        'email_pessoal'     => emailAleatorio(),
        'salt'              => password('Teste@1324'),
        'status'            => 1
    ],
    [
        'matricula'      => 1354826,
        'cod'            => uuid(),
        'empresa'        => 198,
        'tipo'           => 1,
        'nome'           => nomeCompletoAleatorio(),
        'email_trabalho' => emailAleatorio(),
        'telefone_fixo'  => telefoneAleatorio(),
        'documento'      => 67783406815,
        'email_pessoal'  => emailAleatorio(),
        'salt'           => password('Teste@1324'),
        'status'         => 1
    ],
    [
        'cod'              => uuid(),
        'empresa'          => 1,
        'tipo'             => 1,
        'nome'             => 'André Rodrigues',
        'email_trabalho'   => 'andre@markt.club',
        'telefone_celular' => '61981777773',
        'documento'        => 1495180131,
        'salt'             => password('Teste@1324'),
        'status'           => 1
    ],
    [
        'id'             => 100,
        'cod'            => '00956a04-3b7e-446b-9a5e-7a425ce1b408',
        'empresa'        => 1967,
        'tipo'           => 1,
        'nome'           => nomeCompletoAleatorio(),
        'email_trabalho' => emailAleatorio(),
        'telefone_fixo'  => telefoneAleatorio(),
        'documento'      => 67783406815,
        'email_pessoal'  => emailAleatorio(),
        'salt'           => password('Teste@1324'),
        'status'         => 1
    ]
];
