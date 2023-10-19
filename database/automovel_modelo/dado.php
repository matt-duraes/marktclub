<?php

use App\Classes\Geral\Status;

$titulos = [
    'Volkswagen Gol',
    'Chevrolet Onix',
    'Fiat Palio',
    'Ford Ka',
    'Renault Sandero',
    'Hyundai HB20',
    'Toyota Corolla',
    'Honda Civic',
    'Nissan Kicks',
    'Volkswagen Polo',
    'Chevrolet Prisma',
    'Fiat Uno',
    'Ford Fiesta',
    'Renault Logan',
    'Hyundai Creta',
    'Toyota Etios',
    'Honda Fit',
    'Nissan Versa',
    'Volkswagen Fox',
    'Chevrolet Spin',
    'Fiat Siena',
    'Ford Ecosport',
    'Renault Duster',
    'Hyundai Tucson',
    'Toyota Hilux',
    'Honda HR-V',
    'Nissan March',
    'Volkswagen Up!',
    'Chevrolet Tracker',
    'Fiat Toro',
    'Ford Ranger',
    'Renault Captur',
    'Hyundai i30',
    'Toyota RAV4',
    'Honda City',
    'Nissan Sentra',
    'Volkswagen Voyage',
    'Chevrolet Cobalt',
    'Fiat Mobi',
    'Ford Fusion',
    'Renault Fluence',
    'Hyundai Santa Fe',
    'Toyota Camry',
    'Honda Accord',
    'Nissan Altima',
    'Volkswagen Jetta',
    'Chevrolet Cruze',
    'Fiat Cronos',
    'Ford Focus',
    'Renault Megane'
];
$listaStatus = (new Status())->listarNumero();
$seeds = [];
$seeds[] = [
    'uuid'             => 'dc68285f-65e2-4db9-b37c-d216cf4ddd97',
    'id_parceiro_loja' => 1,
    'url'              => 'alguma-coisa',
    'titulo'           => 'Alguma coisa',
    'data_inicio'      => dataPassadaAleatorio(),
    'data_final'       => dataFuturaAleatorio(),
    'status'           => 1
];
for ($i = 0; $i < env('QTD_SEEDS', 50); $i++) {
    $titulo = valorAleatorio($titulos) . $i;
    $seeds[] = [
        'uuid'             => uuid(),
        'id_parceiro_loja' => numeroAleatorio(1, 50),
        'url'              => strSlug($titulo),
        'titulo'           => $titulo,
        'data_inicio'      => dataPassadaAleatorio(),
        'data_final'       => dataFuturaAleatorio(),
        'status'           => valorAleatorio($listaStatus)
    ];
}
return $seeds;
