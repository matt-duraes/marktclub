<?php

use App\Classes\UsuarioIndicacao\Status;

$listaStatus = (new Status())->listarNumero();
$seeds = [];
for ($i = 0; $i < env('QTD_SEEDS', 50); $i++) {
    $seeds[] = [
        'uuid'               => uuid(),
        'id_admin_empresa'   => numeroAleatorio(1, 50),
        'id_usuario_cliente' => numeroAleatorio(1, 50),
        'hash'               => uuid(),
        'nome'               => nomeCompletoAleatorio(),
        'email'              => emailAleatorio(),
        'telefone'           => telefoneAleatorio(),
        'status'             => valorAleatorio($listaStatus)
    ];
}
return $seeds;
