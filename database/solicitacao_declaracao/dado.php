<?php

use App\Classes\Solicitacao\Status;

$listaModeloVersao = [
    'Toyota Corolla'        => ['LE', 'SE', 'XSE', 'Hybrid'],
    'Honda Civic'           => ['LX', 'EX', 'Touring', 'Type R'],
    'Ford Mustang'          => ['EcoBoost', 'GT', 'Shelby GT350', 'Mach 1'],
    'Chevrolet Camaro'      => ['1LS', '2LT', 'SS', 'ZL1'],
    'Volkswagen Golf'       => ['S', 'SE', 'GTI', 'R'],
    'Nissan Altima'         => ['S', 'SR', 'SV', 'Platinum'],
    'BMW 3 Series'          => ['330i', 'M340i', '330e', 'M3'],
    'Audi A4'               => ['Premium', 'Premium Plus', 'Prestige', 'S4'],
    'Mercedes-Benz C-Class' => ['C 300', 'AMG C 43', 'AMG C 63', 'C 350e'],
    'Hyundai Sonata'        => ['SE', 'SEL', 'Limited', 'N Line'],
    'Kia Optima'            => ['LX', 'S', 'EX', 'SX'],
    'Subaru Outback'        => ['Base', 'Premium', 'Limited', 'Touring'],
    'Mazda CX-5'            => ['Sport', 'Touring', 'Grand Touring', 'Signature'],
    'Jeep Wrangler'         => ['Sport', 'Sahara', 'Rubicon', '4xe'],
    'Tesla Model 3'         => ['Standard Range Plus', 'Long Range', 'Performance'],
    'Volvo XC90'            => ['Momentum', 'R-Design', 'Inscription', 'Excellence'],
    'Lexus RX'              => ['RX 350', 'RX 450h', 'RX 350L', 'RX 450hL'],
    'Porsche 911'           => ['Carrera', 'Carrera S', 'Turbo', 'GT3'],
    'Cadillac Escalade'     => ['Luxury', 'Premium Luxury', 'Sport', 'Platinum'],
    'GMC Sierra'            => ['SLE', 'SLT', 'AT4', 'Denali'],
    'Dodge Charger'         => ['SXT', 'GT', 'Scat Pack', 'Hellcat'],
    'Jeep Grand Cherokee'   => ['Laredo', 'Limited', 'Trailhawk', 'SRT'],
    'Chevrolet Silverado'   => ['WT', 'LT', 'LTZ', 'High Country'],
    'Acura MDX'             => ['Standard', 'Technology', 'A-Spec', 'Advance'],
    'Infiniti Q50'          => ['Pure', 'Luxe', 'Sport', 'Red Sport 400']
];
$listaStatus = (new Status())->listarNumero();
$seeds = [];
for ($i = 0; $i < env('QTD_SEEDS', 50); $i++) {
    $modelo = valorAleatorio(array_keys($listaModeloVersao));
    $versao = valorAleatorio($listaModeloVersao[$modelo]);
    $seeds[] = [
        'cod'     => uuid(),
        'empresa' => numeroAleatorio(1, 50),
        'usuario' => numeroAleatorio(1, 50),
        'vinculo' => numeroAleatorio(1, 50),
        'modelo'  => $modelo,
        'versao'  => $versao,
        'status'  => valorAleatorio($listaStatus)
    ];
}
return $seeds;
