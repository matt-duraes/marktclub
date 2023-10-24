<?php

use App\Classes\Solicitacao\Status;

$listaMontadoras = [
    'Toyota', 'Honda', 'Ford', 'Chevrolet', 'Volkswagen (VW)', 'Nissan', 'BMW',
    'Audi', 'Mercedes-Benz', 'Hyundai', 'Kia', 'Subaru', 'Mazda', 'Jeep', 'Tesla',
    'Volvo', 'Lexus', 'Porsche', 'Cadillac', 'GMC', 'Dodge', 'Land Rover', 'Jaguar',
    'Chrysler', 'Buick', 'Fiat', 'Infiniti', 'Mitsubishi', 'Mini', 'Alfa Romeo',
    'Rolls-Royce', 'Aston Martin', 'Maserati', 'Bentley', 'Lamborghini', 'Ferrari',
    'Bugatti', 'McLaren', 'Lotus', 'Genesis', 'Acura', 'Ram', 'Lincoln', 'Smart',
    'Pagani', 'Koenigsegg', 'Spyker', 'Hennessey', 'Rimac', 'Lucid Motors'
];
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
$listaCores = [
    'Vermelho', 'Verde', 'Azul', 'Amarelo', 'Rosa', 'Roxo', 'Laranja', 'Preto',
    'Branco', 'Cinza', 'Marrom', 'Dourado', 'Prateado', 'Turquesa', 'Magenta',
    'Bege', 'Ciano', 'Lavanda', 'Violeta', 'Salmão', 'Verde Limão', 'Azul Celeste',
    'Coral', 'Índigo', 'Terracota', 'Marfim', 'Menta', 'Bordô', 'Pêssego', 'Aqua',
    'Ocre', 'Taupe', 'Siena', 'Champagne', 'Âmbar', 'Rubi', 'Esmeralda', 'Ágata',
    'Jade', 'Cereja', 'Oliveira', 'Safira', 'Perola', 'Malva', 'Malibu', 'Topázio',
    'Carmesim', 'Ametista', 'Maracuja'
];
$mensagem = '
    Espero que esta mensagem a encontre bem e feliz.
    Hoje, neste dia especial, quero fazer uma pergunta que mudará nossas vidas para sempre.
    Você aceita se casar comigo? Você é a pessoa com quem quero construir meu futuro e ser seu companheiro para toda a vida.
';
$listaStatus = (new Status())->listarNumero();
$seeds = [];
for ($i = 0; $i < env('QTD_SEEDS', 50); $i++) {
    $estado = estadoAleatorio();
    $cidade = cidadeAleatorio($estado);
    $modelo = valorAleatorio(array_keys($listaModeloVersao));
    $versao = valorAleatorio($listaModeloVersao[$modelo]);
    $seeds[] = [
        'uuid'               => uuid(),
        'id_admin_empresa'   => numeroAleatorio(1, 50),
        'id_usuario_cliente' => numeroAleatorio(1, 50),
        'endereco_estado'    => $estado,
        'endereco_cidade'    => $cidade,
        'montadora'          => valorAleatorio($listaMontadoras),
        'modelo'             => $modelo,
        'versao'             => $versao,
        'cor'                => valorAleatorio($listaCores),
        'mensagem'           => $mensagem,
        'status'             => valorAleatorio($listaStatus)
    ];
}
return $seeds;
