<?php

use App\Classes\UsuarioIndicacao\Status;

$listaStatus = (new Status())->listarNumero();
$seeds = [];
$default = [];
for ($i = 0; $i < env('QTD_SEEDS', 20); $i++) {
    $seeds[] = [
        'cod'                => uuid(),
        'id_admin_empresa'   => numeroAleatorio(1, 5),
        'id_usuario_cliente' => numeroAleatorio(1, env('QTD_SEEDS', 20)),
        'hash'               => uuid(),
        'nome'               => nomeCompletoAleatorio(),
        'email'              => emailAleatorio(),
        'telefone'           => telefoneAleatorio(),
        'status'             => valorAleatorio($listaStatus)
    ];
}
return array_merge($default, $seeds);
