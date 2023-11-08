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
    ],
    [
        'id'            => 5,
        'cod'           => '114907f3-effc-43db-8211-64e684576f55',
        'empresa'       => 2,
        'tipo'          => 1,
        'nome'          => 'Usuário ANAFE',
        'documento'     => '01234567890',
        'email_pessoal' => 'teste@anafecard.com.br',
        'salt'          => password('Teste@1324'),
        'status'        => 1
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
    ],
    [
        'cod'           => '7e3795ef-ef0c-49af-bfda-afd948a23d6e',
        'empresa'       => 1,
        'tipo'          => 1,
        'nome'          => 'Usuário duplicado',
        'documento'     => '52910622070',
        'email_pessoal' => 'usuario@duplicado.com',
        'salt'          => password('Teste@1324'),
        'status'        => 1
    ],
    [
        'cod'            => 'e0f96058-18a8-48d6-843d-b593243cb7ee',
        'empresa'        => 1,
        'tipo'           => 2,
        'documento'      => 52149649004,
        'status'         => 5,
        'titular'        => 1
    ],
    [
        'cod'           => '32e9c475-bbbc-4cd6-854c-6cf1bb88fd8d',
        'empresa'       => 2,
        'tipo'          => 1,
        'nome'          => 'Usuário duplicado',
        'documento'     => '52910622070',
        'email_pessoal' => 'usuario@duplicado.com',
        'salt'          => password('Teste@1324'),
        'status'        => 1
    ],
];
return $seeds;
