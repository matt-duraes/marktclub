<?php

use App\Classes\EnqueteSatisfacao\Atendimento;
use App\Classes\EnqueteSatisfacao\Navegar;
use App\Classes\EnqueteSatisfacao\Procura;
use App\Classes\EnqueteSatisfacao\Status;
use App\Classes\EnqueteSatisfacao\Suporte;

$seeds = [];
for ($i = 0; $i < env('QTD_SEEDS', 50); $i++) {
    $seeds[] = [
        'uuid'               => uuid(),
        'id_usuario_cliente' => 1,
        'navegar'            => valorAleatorio((new Navegar())->listarNumero()),
        'procura'            => valorAleatorio((new Procura())->listarNumero()),
        'suporte'            => valorAleatorio((new Suporte())->listarNumero()),
        'atendimento'        => valorAleatorio((new Atendimento())->listarNumero()),
        'sistemas_clube'     => null,
        'comentario'         => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.
         Suscipit, assumenda! Accusantium inventore ad velit iusto dicta mollitia minus officiis saepe!',
        'status'             => valorAleatorio((new Status())->listarNumero())
    ];
}

return $seeds;
