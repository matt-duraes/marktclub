<?php

use App\Classes\EnqueteSatisfacao\Atendimento;
use App\Classes\EnqueteSatisfacao\Navegar;
use App\Classes\EnqueteSatisfacao\Procura;
use App\Classes\EnqueteSatisfacao\Status;
use App\Classes\EnqueteSatisfacao\Suporte;

return [
    [
        'uuid'               => uuid(),
        'id_admin_empresa'   => 1,
        'id_usuario_cliente' => 1,
        'navegar'            => valorAleatorio((new Navegar())->listarNumero()),
        'procura'            => valorAleatorio((new Procura())->listarNumero()),
        'suporte'            => valorAleatorio((new Suporte())->listarNumero()),
        'atendimento'        => valorAleatorio((new Atendimento())->listarNumero()),
        'sistemas_clube'     => '[]',
        'comentario'         => '',
        'status'             => valorAleatorio((new Status())->listarNumero())
    ],
    [
        'uuid'               => uuid(),
        'id_admin_empresa'   => 1,
        'id_usuario_cliente' => 1,
        'navegar'            => valorAleatorio((new Navegar())->listarNumero()),
        'procura'            => valorAleatorio((new Procura())->listarNumero()),
        'suporte'            => valorAleatorio((new Suporte())->listarNumero()),
        'atendimento'        => valorAleatorio((new Atendimento())->listarNumero()),
        'sistemas_clube'     => '[]',
        'comentario'         => '',
        'status'             => valorAleatorio((new Status())->listarNumero())
    ],
    [
        'uuid'               => uuid(),
        'id_admin_empresa'   => 1,
        'id_usuario_cliente' => 1,
        'navegar'            => valorAleatorio((new Navegar())->listarNumero()),
        'procura'            => valorAleatorio((new Procura())->listarNumero()),
        'suporte'            => valorAleatorio((new Suporte())->listarNumero()),
        'atendimento'        => valorAleatorio((new Atendimento())->listarNumero()),
        'sistemas_clube'     => '[]',
        'comentario'         => '',
        'status'             => valorAleatorio((new Status())->listarNumero())
    ],
    [
        'uuid'               => uuid(),
        'id_admin_empresa'   => 1,
        'id_usuario_cliente' => 1,
        'navegar'            => valorAleatorio((new Navegar())->listarNumero()),
        'procura'            => valorAleatorio((new Procura())->listarNumero()),
        'suporte'            => valorAleatorio((new Suporte())->listarNumero()),
        'atendimento'        => valorAleatorio((new Atendimento())->listarNumero()),
        'sistemas_clube'     => '[]',
        'comentario'         => '',
        'status'             => valorAleatorio((new Status())->listarNumero())
    ],
    [
        'uuid'               => uuid(),
        'id_admin_empresa'   => 1,
        'id_usuario_cliente' => 1,
        'navegar'            => valorAleatorio((new Navegar())->listarNumero()),
        'procura'            => valorAleatorio((new Procura())->listarNumero()),
        'suporte'            => valorAleatorio((new Suporte())->listarNumero()),
        'atendimento'        => valorAleatorio((new Atendimento())->listarNumero()),
        'sistemas_clube'     => '[]',
        'comentario'         => '',
        'status'             => valorAleatorio((new Status())->listarNumero())
    ],
    [
        'uuid'               => uuid(),
        'id_admin_empresa'   => 1,
        'id_usuario_cliente' => 1,
        'navegar'            => valorAleatorio((new Navegar())->listarNumero()),
        'procura'            => valorAleatorio((new Procura())->listarNumero()),
        'suporte'            => valorAleatorio((new Suporte())->listarNumero()),
        'atendimento'        => valorAleatorio((new Atendimento())->listarNumero()),
        'sistemas_clube'     => '[]',
        'comentario'         => '',
        'status'             => valorAleatorio((new Status())->listarNumero())
    ]
];