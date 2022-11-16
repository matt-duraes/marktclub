<?php

return [
    [
        'cod' => '5595203c-f7b1-4211-9981-bf09eb236b35',
        'empresa' => 1,
        'tipo' => 1,
        'nome' => nomeAleatorio(),
        'documento' => cpfAleatorio(),
        'salt' => password('Teste@1324'),
        'status' => 1
    ],
    [
        'cod' => uuid(),
        'empresa' => 1,
        'tipo' => 1,
        'nome' => nomeAleatorio(),
        'documento' => cpfAleatorio(),
        'salt' => password('Teste@1324'),
        'status' => 1
    ],
    [
        'cod' => uuid(),
        'empresa' => 1,
        'tipo' => 1,
        'nome' => nomeAleatorio(),
        'documento' => cpfAleatorio(),
        'salt' => password('Teste@1324'),
        'status' => 1
    ],
    [
        'matricula' => 1354826,
        'cod' => uuid(),
        'empresa' => 198,
        'tipo' => 1,
        'nome' => nomeAleatorio(),
        'email_trabalho' => emailAleatorio(),
        'telefone_fixo' => telefoneAleatorio(),
        'documento' => 67783406815,
        'salt' => password('Teste@1324'),
        'status' => 1
    ]
];
