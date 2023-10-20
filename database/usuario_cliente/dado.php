<?php

use App\Classes\UsuarioCliente\Status;
use App\Classes\UsuarioCliente\TipoUsuario;

$listaTipoUsuario = (new TipoUsuario())->listarNumero();
$listaStatus = (new Status())->listarNumero();
$seeds = [
    [
        'id'            => 1,
        'cod'           => '5595203c-f7b1-4211-9981-bf09eb236b35',
        'empresa'       => 1,
        'tipo'          => 1,
        'nome'          => nomeCompletoAleatorio(),
        'documento'     => cpfAleatorio(),
        'email_pessoal' => emailAleatorio(),
        'salt'          => password('Teste@1324'),
        'status'        => 1
    ],
    [
        'id'            => 2,
        'cod'           => '87cd8f94-601e-4e8e-b800-7f42a75fc0e1',
        'empresa'       => 1,
        'tipo'          => 1,
        'nome'          => nomeCompletoAleatorio(),
        'documento'     => cpfAleatorio(),
        'email_pessoal' => emailAleatorio(),
        'salt'          => password('Teste@1324'),
        'status'        => 1
    ],
    [
        'id'            => 3,
        'cod'           => 'cdc41730-abc7-4b51-abd7-698e2cb3c0b2',
        'empresa'       => 1,
        'tipo'          => 1,
        'nome'          => 'Usuário de Teste',
        'documento'     => '01234567890',
        'email_pessoal' => 'teste@markt.club',
        'salt'          => password('Teste@1324'),
        'status'        => 1
    ],
    [
        'id'            => 4,
        'cod'           => 'cdc41730-abc7-4b51-abd7-698e2cb3c0b1',
        'empresa'       => 1,
        'tipo'          => 3,
        'nome'          => 'Teste CVS',
        'documento'     => '55525957000',
        'email_pessoal' => 'teste2@cvs.club',
        'salt'          => password('Teste@1324'),
        'status'        => 1
    ]
];
for ($i = 0; $i < env('QTD_SEEDS', 50); $i++) {
    $tipo = valorAleatorio($listaTipoUsuario);
    $seeds[] = [
        'cod'           => uuid(),
        'empresa'       => 1,
        'titular'       => ($tipo != 2) ? null : 1,
        'tipo'          => $tipo,
        'nome'          => nomeCompletoAleatorio(),
        'documento'     => cpfAleatorio(),
        'email_pessoal' => emailAleatorio(),
        'salt'          => password('Teste@' . $i),
        'status'        => valorAleatorio($listaStatus)
    ];
}
return $seeds;
