<?php

use App\Classes\Saude\Localizacao;
use App\Classes\Saude\Operadora;
use App\Classes\Saude\Status;
use App\Classes\Saude\Tipo;

return [
    [
        'uuid'                   => '32dd2783-daf2-4cf4-be78-6f43e3f801c5',
        'id_empresa'             => numeroAleatorio(1, 10),
        'id_usuario'             => numeroAleatorio(1, 15),
        'data_nascimento'        => dataPassadaAleatorio(),
        'quantidade_dependentes' => numeroAleatorio(1, 20),
        'operadora'              => valorAleatorio((new Operadora())->listarNumero()),
        'acomodacao'             => 'enfermaria',
        'regiao'                 => valorAleatorio((new Localizacao())->listarNumero()),
        'valor_titular'          => number_format(numeroAleatorio(1, 1000), 2, ',', '.'),
        'valor_dependentes'      => '[]',
        'valor_total'            => number_format(numeroAleatorio(1, 1000), 2, ',', '.'),
        'tipo'                   => valorAleatorio((new Tipo())->listarNumero()),
        'status'                 => valorAleatorio((new Status())->listarNumero())
    ],
    [
        'uuid'                   => uuid(),
        'id_empresa'             => numeroAleatorio(1, 10),
        'id_usuario'             => numeroAleatorio(1, 15),
        'data_nascimento'        => dataPassadaAleatorio(),
        'quantidade_dependentes' => numeroAleatorio(1, 20),
        'operadora'              => valorAleatorio((new Operadora())->listarNumero()),
        'acomodacao'             => 'enfermaria',
        'regiao'                 => valorAleatorio((new Localizacao())->listarNumero()),
        'valor_titular'          => number_format(numeroAleatorio(1, 1000), 2, ',', '.'),
        'valor_dependentes'      => '[]',
        'valor_total'            => number_format(numeroAleatorio(1, 1000), 2, ',', '.'),
        'tipo'                   => valorAleatorio((new Tipo())->listarNumero()),
        'status'                 => valorAleatorio((new Status())->listarNumero())
    ]
];
