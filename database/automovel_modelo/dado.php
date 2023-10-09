<?php

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

$dado = [];

for ($i = 1; $i < 50; $i++) {
    $slug = strtolower(preg_replace('/[ -]+/', '-', $titulos[$i]));
    $dado[] = [
        'id'               => $i,
        'uuid'             => uuid(),
        'id_parceiro_loja' => 1001,
        'titulo'           => $titulos[$i],
        'url'              => $slug,
        'data_inicio'      => dataPassadaAleatorio(),
        'data_final'       => dataFuturaAleatorio(),
        'status'           => 1
    ];
}

return $dado;
