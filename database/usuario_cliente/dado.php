<?php

return [
    [
        'id' => 1,
        'cod' => '5595203c-f7b1-4211-9981-bf09eb236b35',
        'empresa' => 1,
        'tipo' => 1,
        'nome' => nomeCompletoAleatorio(),
        'documento' => cpfAleatorio(),
        'salt' => password('Teste@1324'),
        'status' => 1
    ],
    [
        'id' => 2,
        'cod' => '87cd8f94-601e-4e8e-b800-7f42a75fc0e1',
        'empresa' => 1,
        'tipo' => 1,
        'nome' => nomeCompletoAleatorio(),
        'documento' => cpfAleatorio(),
        'salt' => password('Teste@1324'),
        'status' => 1
    ],
    [
        'id' => 3,
        'cod' => 'c91d0f54-d166-456e-9f21-e072722faa34',
        'empresa' => 1,
        'tipo' => 1,
        'nome' => nomeCompletoAleatorio(),
        'documento' => cpfAleatorio(),
        'salt' => password('Teste@1324'),
        'status' => 1
    ],
    [
        'cod' => uuid(),
        'empresa' => 2,
        'tipo' => 1,
        'nome' => 'Usuario Empresa 2 = 1',
        'documento' => cpfAleatorio(),
        'salt' => password('Teste@1324'),
        'status' => 1
    ],
    [
        'cod' => uuid(),
        'empresa' => 2,
        'tipo' => 1,
        'nome' => 'Usuario Empresa 2 = 2',
        'documento' => cpfAleatorio(),
        'salt' => password('Teste@1324'),
        'status' => 1
    ],
    [
        'cod' => uuid(),
        'empresa' => 2,
        'tipo' => 1,
        'nome' => 'Usuario Empresa 2 = 3',
        'documento' => cpfAleatorio(),
        'salt' => password('Teste@1324'),
        'status' => 1
    ],
    [
        'matricula' => 1354826,
        'cod' => uuid(),
        'empresa' => 198,
        'tipo' => 1,
        'nome' => nomeCompletoAleatorio(),
        'email_trabalho' => emailAleatorio(),
        'telefone_fixo' => telefoneAleatorio(),
        'documento' => 67783406815,
        'salt' => password('Teste@1324'),
        'status' => 1
    ]
];
