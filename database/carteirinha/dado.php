<?php

use App\Classes\Carteirinha\Status;

$seeds = [
    [
        'uuid'             => uuid(),
        'id_admin_empresa' => 1,
        'bg_frente'        => 'card_bg_asagu.png',
        'bg_fundo'         => 'asagu_catao.png',
        'status'           => (new Status(Status::ATIVO))->numero()
    ]
];

$listaStatus = (new Status())->listarNumero();

for ($i = 0; $i < env('QTD_SEEDS', 50); $i++) {
    $seeds[] = [
        'uuid'             => uuid(),
        'id_admin_empresa' => numeroAleatorio(1, 50),
        'bg_frente'        => 'card_bg_asagu.png',
        'bg_fundo'         => 'asagu_catao.png',
        'status'           => valorAleatorio($listaStatus)
    ];
}

return $seeds;
