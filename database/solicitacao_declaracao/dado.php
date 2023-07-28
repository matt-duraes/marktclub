<?php

use App\Classes\SolicitacaoDeclaracao\Status;
use App\Classes\SolicitacaoDeclaracao\Tipo;

return [
    [
        'uuid'             => 'abc58a05-14f8-49ee-bf9c-40cffeacc9d8',
        'id_admin_empresa' => 1,
        'id_usuario'       => 1,
        'vinculo'          => '890713a200a9e45aa85e2ae67aa41e74',
        'tipo'             => valorAleatorio((new Tipo())->listarNumero()),
        'status'           => valorAleatorio((new Status())->listarNumero())
    ],
    [
        'uuid'             => uuid(),
        'id_admin_empresa' => 1,
        'id_usuario'       => 1,
        'vinculo'          => '9792e058562303f9e7e0604c5117c569',
        'tipo'             => valorAleatorio((new Tipo())->listarNumero()),
        'status'           => valorAleatorio((new Status())->listarNumero())
    ],
    [
        'uuid'             => uuid(),
        'id_admin_empresa' => 1,
        'id_usuario'       => 1,
        'vinculo'          => '5d20bebb-36d5-47ce-8bc8-178309983a9a',
        'tipo'             => valorAleatorio((new Tipo())->listarNumero()),
        'status'           => valorAleatorio((new Status())->listarNumero())
    ],
    [
        'uuid'             => uuid(),
        'id_admin_empresa' => 1,
        'id_usuario'       => 1,
        'vinculo'          => 'f10e05c0-5b02-4bff-8e22-719a8797f0d6',
        'tipo'             => valorAleatorio((new Tipo())->listarNumero()),
        'status'           => valorAleatorio((new Status())->listarNumero())
    ],
    [
        'uuid'             => uuid(),
        'id_admin_empresa' => 1,
        'id_usuario'       => 1,
        'vinculo'          => '4502e7e8-9359-470e-9588-0a1501449675',
        'tipo'             => valorAleatorio((new Tipo())->listarNumero()),
        'status'           => valorAleatorio((new Status())->listarNumero())
    ],
    [
        'uuid'             => uuid(),
        'id_admin_empresa' => 1,
        'id_usuario'       => 1,
        'vinculo'          => 'adca39ea4a6d6bcc51eba8afcdb54eaa',
        'tipo'             => valorAleatorio((new Tipo())->listarNumero()),
        'status'           => valorAleatorio((new Status())->listarNumero())
    ],
    [
        'uuid'             => uuid(),
        'id_admin_empresa' => 1,
        'id_usuario'       => 1,
        'vinculo'          => 'f9cbb6ae-b847-43cf-b9b8-6f72b67789df',
        'tipo'             => valorAleatorio((new Tipo())->listarNumero()),
        'status'           => valorAleatorio((new Status())->listarNumero())
    ]
];
