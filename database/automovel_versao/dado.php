<?php

$titulos = [
    "Volkswagen Gol Comfortline",
    "Chevrolet Onix LT",
    "Fiat Palio Essence",
    "Ford Ka SE",
    "Renault Sandero Expression",
    "Hyundai HB20 Vision",
    "Toyota Corolla XLE",
    "Honda Civic EX",
    "Nissan Kicks SV",
    "Volkswagen Polo Highline",
    "Chevrolet Prisma LTZ",
    "Fiat Uno Drive",
    "Ford Fiesta Titanium",
    "Renault Logan Dynamique",
    "Hyundai Creta Limited",
    "Toyota Etios Platinum",
    "Honda Fit LX",
    "Nissan Versa SL",
    "Volkswagen Fox Connect",
    "Chevrolet Spin Activ",
    "Fiat Siena Attractive",
    "Ford Ecosport Titanium",
    "Renault Duster Dynamique",
    "Hyundai Tucson Ultimate",
    "Toyota Hilux SRV",
    "Honda HR-V Touring",
    "Nissan March SV",
    "Volkswagen Up! Move",
    "Chevrolet Tracker Premier",
    "Fiat Toro Volcano",
    "Ford Ranger Wildtrak",
    "Renault Captur Intense",
    "Hyundai i30 N-Line",
    "Toyota RAV4 Adventure",
    "Honda City EXL",
    "Nissan Sentra SL",
    "Volkswagen Voyage Trendline",
    "Chevrolet Cobalt Elite",
    "Fiat Mobi Like",
    "Ford Fusion Titanium",
    "Renault Fluence Dynamique",
    "Hyundai Santa Fe Limited",
    "Toyota Camry XSE",
    "Honda Accord Touring",
    "Nissan Altima Platinum",
    "Volkswagen Jetta GLI",
    "Chevrolet Cruze LTZ",
    "Fiat Cronos Precision",
    "Ford Focus SEL",
    "Renault Megane GT"
];

$dado = [];

for ($i = 1; $i < 50; $i++) {
    $dado[] = [
        'id' => $i,
        'uuid' => uuid(),
        'id_automovel_modelo' => $i,
        'titulo' => $titulos[$i],
        'valor_de' => rand(100000, 200000),
        'valor_por' => rand(100000, 200000),
        'status' => 1
    ];
}

return $dado;
