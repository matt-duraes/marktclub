<?php

use App\Classes\Saude\Operadora;
use App\Classes\Saude\Plano;
use App\Classes\Saude\Regiao;
use App\Classes\Saude\Status;

return [
    [
        'uuid'                   => '32dd2783-daf2-4cf4-be78-6f43e3f801c5',
        'id_admin_empresa'       => 1,
        'id_usuario'             => 1,
        'titular'                => dataPassadaAleatorio(),
        'quantidade_dependentes' => numeroAleatorio(1, 20),
        'operadora'              => 1,
        'acomodacao'             => 'enfermaria',
        'plano'                  => '',
        'regiao'                 => '',
        'valor_titular'          => number_format(numeroAleatorio(1, 1000), 2, ',', '.'),
        'dependentes'            => '[]',
        'valor_total'            => number_format(numeroAleatorio(1, 1000), 2, ',', '.'),
        'status'                 => valorAleatorio((new Status())->listarNumero())
    ],
    [
        'uuid'                   => uuid(),
        'id_admin_empresa'       => 1,
        'id_usuario'             => 1,
        'titular'                => dataPassadaAleatorio(),
        'quantidade_dependentes' => numeroAleatorio(1, 20),
        'operadora'              => 1,
        'acomodacao'             => 'enfermaria',
        'plano'                  => '',
        'regiao'                 => '',
        'valor_titular'          => number_format(numeroAleatorio(1, 1000), 2, ',', '.'),
        'dependentes'            => '[]',
        'valor_total'            => number_format(numeroAleatorio(1, 1000), 2, ',', '.'),
        'status'                 => valorAleatorio((new Status())->listarNumero())
    ]
];
