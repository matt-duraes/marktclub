<?php

use App\Classes\EnqueteSatisfacao\Atendimento;
use App\Classes\EnqueteSatisfacao\Navegar;
use App\Classes\EnqueteSatisfacao\Procura;
use App\Classes\EnqueteSatisfacao\Status;
use App\Classes\EnqueteSatisfacao\Suporte;

$seeds = [];
for ($i = 0; $i < env('QTD_SEEDS', 500); $i++) {
    $seeds[] = [
        'uuid'               => uuid(),
        'id_usuario_cliente' => valorAleatorio([
            1, 2, 3, 100, 104, 105, 110, 1126, 1127,
            1128, 1129, 1130, 1131, 1132, 1133
        ]),
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
