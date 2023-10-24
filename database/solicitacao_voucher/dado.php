<?php

use App\Classes\SolicitacaoVoucher\Status;
use App\Classes\SolicitacaoVoucher\Tipo;
use App\Classes\SolicitacaoVoucher\TipoUsuario;

$listaTitulos = [
    'Voucher de Compras',
    'Voucher de Experiência',
    'Voucher de Spa e Relaxamento',
    'Voucher de Jantar Romântico',
    'Voucher de Massagem Terapêutica',
    'Voucher de Beleza e Bem-Estar',
    'Voucher de Aventura ao Ar Livre',
    'Voucher de Presente Especial',
    'Voucher de Fim de Semana Relaxante',
    'Voucher de Cinema e Lanche',
    'Voucher de Desconto Exclusivo',
    'Voucher de Serviço Personalizado',
    'Voucher de Viagem Inesquecível',
    'Voucher de Aula de Culinária',
    'Voucher de Presente Surpresa',
    'Voucher de Comida Gourmet',
    'Voucher de Sessão de Fotos Profissional',
    'Voucher de Acesso VIP',
    'Voucher de Treinamento Esportivo',
    'Voucher de Aula de Dança',
    'Voucher de Dia de Spa',
    'Voucher de Atividade ao Ar Livre',
    'Voucher de Compras Online',
    'Voucher de Presente Personalizado',
    'Voucher de Tratamento de Beleza',
];
$listaTipo = (new Tipo())->listarNumero();
$listaTipoUsuario = (new TipoUsuario())->listarNumero();
$listaStatus = (new Status())->listarNumero();
$seeds = [
    [
        'cod'                  => uuid(),
        'empresa'              => 1,
        'usuario'              => 1,
        'vinculo'              => '890713a200a9e45aa85e2ae67aa41e74',
        'titulo'               => valorAleatorio($listaTitulos),
        'tipo'                 => 1,
        'tipo_usuario'         => 1,
        'valor'                => number_format(numeroAleatorio(), 2, thousands_separator: ''),
        'codigo'               => strtoupper(uniqid()),
        'quantidade_voucher'   => 9,
        'documento_dependente' => cpfAleatorio(),
        'data_vencimento'      => dataAdicionar(agora(), 30, 'dia'),
        'data_validacao'       => null,
        'status'               => 2
    ],
    [
        'cod'             => uuid(),
        'tipo'            => 1,
        'tipo_usuario'    => 1,
        'vinculo'         => '5d20bebb-36d5-47ce-8bc8-178309983a9a',
        'usuario'         => 1,
        'empresa'         => 1,
        'codigo'          => 'abcde12345',
        'data_criacao'    => dataRemover(agora(), 10, 'minutos', 'Y-m-d H:i:s'),
        'data_vencimento' => dataAdicionar(agora(), 10, 'dia', 'Y-m-d'),
        'status'          => 3,
    ],
    [
        'cod'             => uuid(),
        'tipo'            => 1,
        'tipo_usuario'    => 1,
        'vinculo'         => '4502e7e8-9359-470e-9588-0a1501449675',
        'usuario'         => 1,
        'empresa'         => 1,
        'codigo'          => rand(10000000, 99999999),
        'data_criacao'    => dataRemover(agora(), 10, 'dia', 'Y-m-d H:i:s'),
        'data_vencimento' => dataRemover(agora(), 5, 'dia', 'Y-m-d'),
        'status'          => 3,
    ],
    [
        'cod'             => uuid(),
        'tipo'            => 1,
        'tipo_usuario'    => 1,
        'vinculo'         => 'adca39ea4a6d6bcc51eba8afcdb54eaa',
        'usuario'         => 1,
        'empresa'         => 1,
        'data_criacao'    => dataRemover(agora(), 1, 'dia', 'Y-m-d H:i:s'),
        'data_vencimento' => dataAdicionar(hoje(), 4, 'dias'),
        'codigo'          => '123123123',
        'status'          => 1,
    ],
    [
        'cod'             => uuid(),
        'tipo'            => 1,
        'tipo_usuario'    => 1,
        'vinculo'         => 'adca39ea4a6d6bcc51eba8afcdb54eaa',
        'usuario'         => 3,
        'empresa'         => 1,
        'codigo'          => rand(10000000, 99999999),
        'data_criacao'    => dataRemover(agora(), 10, 'dia', 'Y-m-d H:i:s'),
        'data_vencimento' => dataRemover(agora(), 5, 'dia', 'Y-m-d'),
        'status'          => 3,
    ],
    [
        'cod'             => uuid(),
        'tipo'            => 1,
        'tipo_usuario'    => 1,
        'vinculo'         => '7b1476c3-2627-490c-a0cf-dff7b9196b00',
        'usuario'         => 100,
        'empresa'         => 1967,
        'codigo'          => rand(10000000, 99999999),
        'data_criacao'    => dataPrimeiroDiaMes(hoje() . ' 00:00:00'),
        'data_vencimento' => dataPrimeiroDiaMes(hoje()),
        'status'          => 2,
    ]
];
for ($i = 0; $i < env('QTD_SEEDS', 50); $i++) {
    $valor = numeroAleatorio(1, 100000);
    $seeds[] = [
        'cod'                  => uuid(),
        'empresa'              => 1,
        'usuario'              => 1,
        'vinculo'              => uuid(),
        'titulo'               => valorAleatorio($listaTitulos),
        'tipo'                 => valorAleatorio($listaTipo),
        'tipo_usuario'         => valorAleatorio($listaTipoUsuario),
        'valor'                => number_format($valor, 2, thousands_separator: ''),
        'codigo'               => strtoupper(uniqid()),
        'quantidade_voucher'   => numeroAleatorio(1, 9),
        'documento_dependente' => cpfAleatorio(),
        'data_vencimento'      => dataAdicionar(agora(), 10, 'dia'),
        'data_validacao'       => null,
        'status'               => valorAleatorio($listaStatus)
    ];
}
return $seeds;
