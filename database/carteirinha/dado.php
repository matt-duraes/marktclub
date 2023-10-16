<?php

use App\Classes\Carteirinha\Status;

$listaStatus = (new Status())->listarNumero();
$seeds = [];
for ($i = 0; $i < env('QTD_SEEDS', 50); $i++) {
    $seeds[] = [
        'uuid'             => uuid(),
        'id_admin_empresa' => numeroAleatorio(1, 50),
        'bg_frente'        => 'card_bg_asagu.png',
        'bg_fundo'         => 'asagu_catao.png',
        'texto'            => "Texto $i",
        'texto_perdido'    => "Perdido $i",
        'status'           => valorAleatorio($listaStatus)
    ];
}
return $seeds;
