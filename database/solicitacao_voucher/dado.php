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
$seeds = [];
$seeds[] = [
    'cod'                  => uuid(),
    'empresa'              => numeroAleatorio(1, 50),
    'usuario'              => numeroAleatorio(1, 50),
    'vinculo'              => '4502e7e8-9359-470e-9588-0a1501449675',
    'titulo'               => valorAleatorio($listaTitulos),
    'tipo'                 => 1,
    'tipo_usuario'         => 1,
    'valor'                => number_format(numeroAleatorio(), 2, thousands_separator: ''),
    'codigo'               => strtoupper(uniqid()),
    'quantidade_voucher'   => numeroAleatorio(1, 9),
    'documento_dependente' => cpfAleatorio(),
    'data_vencimento'      => dataAdicionar(agora(), 10, 'dia'),
    'data_validacao'       => null,
    'status'               => 1
];
for ($i = 0; $i < env('QTD_SEEDS', 50); $i++) {
    $valor = numeroAleatorio(1, 100000);
    $seeds[] = [
        'cod'                  => uuid(),
        'empresa'              => numeroAleatorio(1, 50),
        'usuario'              => numeroAleatorio(1, 50),
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
