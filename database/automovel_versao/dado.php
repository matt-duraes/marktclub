<?php

use App\Classes\IndicacaoAutomovel\Status;

$titulos = [
    'Volkswagen Gol Comfortline',
    'Chevrolet Onix LT',
    'Fiat Palio Essence',
    'Ford Ka SE',
    'Renault Sandero Expression',
    'Hyundai HB20 Vision',
    'Toyota Corolla XLE',
    'Honda Civic EX',
    'Nissan Kicks SV',
    'Volkswagen Polo Highline',
    'Chevrolet Prisma LTZ',
    'Fiat Uno Drive',
    'Ford Fiesta Titanium',
    'Renault Logan Dynamique',
    'Hyundai Creta Limited',
    'Toyota Etios Platinum',
    'Honda Fit LX',
    'Nissan Versa SL',
    'Volkswagen Fox Connect',
    'Chevrolet Spin Activ',
    'Fiat Siena Attractive',
    'Ford Ecosport Titanium',
    'Renault Duster Dynamique',
    'Hyundai Tucson Ultimate',
    'Toyota Hilux SRV',
    'Honda HR-V Touring',
    'Nissan March SV',
    'Volkswagen Up! Move',
    'Chevrolet Tracker Premier',
    'Fiat Toro Volcano',
    'Ford Ranger Wildtrak',
    'Renault Captur Intense',
    'Hyundai i30 N-Line',
    'Toyota RAV4 Adventure',
    'Honda City EXL',
    'Nissan Sentra SL',
    'Volkswagen Voyage Trendline',
    'Chevrolet Cobalt Elite',
    'Fiat Mobi Like',
    'Ford Fusion Titanium',
    'Renault Fluence Dynamique',
    'Hyundai Santa Fe Limited',
    'Toyota Camry XSE',
    'Honda Accord Touring',
    'Nissan Altima Platinum',
    'Volkswagen Jetta GLI',
    'Chevrolet Cruze LTZ',
    'Fiat Cronos Precision',
    'Ford Focus SEL',
    'Renault Megane GT'
];
$listaStatus = (new Status())->listarNumero();
$seeds = [];
$seeds[] = [
    'uuid'                => 'dc68285f-65e2-4db9-b37c-d216cf4ddd97',
    'id_automovel_modelo' => numeroAleatorio(1, 50),
    'titulo'              => valorAleatorio($titulos),
    'valor_de'            => numeroAleatorio(1, 100000),
    'valor_por'           => numeroAleatorio(1, 10000),
    'status'              => 1
];
for ($i = 0; $i < env('QTD_SEEDS', 50); $i++) {
    $seeds[] = [
        'uuid'                => uuid(),
        'id_automovel_modelo' => numeroAleatorio(1, 50),
        'titulo'              => valorAleatorio($titulos),
        'valor_de'            => numeroAleatorio(1, 100000),
        'valor_por'           => numeroAleatorio(1, 10000),
        'status'              => valorAleatorio($listaStatus)
    ];
}
return $seeds;
