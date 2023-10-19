<?php

$dado = [
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
        'id'                => 104,
        'cod'               => uuid(),
        'empresa'           => 1,
        'tipo'              => 1,
        'nome'              => 'Usuário para ativar',
        'documento'         => '44609809087',
        'email_pessoal'     => emailAleatorio(),
        'status'            => 2
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
        'documento'         => '55525957000',
        'email_pessoal'     => 'teste@markt.club',
        'salt'              => password('Teste@1324'),
        'status'            => 1
    ],
    [
        'cod'               => 'cdc41730-abc7-4b51-abd7-698e2cb3c0b4',
        'empresa'           => 1,
        'tipo'              => 3,
        'nome'              => 'Teste cvs',
        'documento'         => '01767056001',
        'email_pessoal'     => 'tessste@markt.club',
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
    ],
    [
        'id'             => 110,
        'cod'            => '0ab2712a-625f-4588-85e4-33aa68288915',
        'empresa'        => 1,
        'tipo'           => 1,
        'nome'           => 'Usuário Silium',
        'email_trabalho' => emailAleatorio(),
        'telefone_fixo'  => telefoneAleatorio(),
        'documento'      => 91122519095,
        'email_pessoal'  => emailAleatorio(),
        'salt'           => password('Teste@1324'),
        'status'         => 1
    ]
];

for ($i = 2; $i <= 50; $i++) {
    $dado[] = [
        'id'                => $i,
        'cod'               => uuid(),
        'empresa'           => 1,
        'tipo'              => 1,
        'nome'              => nomeCompletoAleatorio(),
        'documento'         => cpfAleatorio(),
        'email_pessoal'     => emailAleatorio(),
        'salt'              => password('Teste@1324'),
        'status'            => 1
    ];
}

return $dado;
