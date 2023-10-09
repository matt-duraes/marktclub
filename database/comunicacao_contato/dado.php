<?php

use App\Classes\ComunicacaoContato\Status;

$listaStatus = (new Status())->listarNumero();

$seeds = [];
for ($i = 0; $i < env('QTD_SEEDS', 50); $i++) {
    $seeds[] = [
        'uuid'             => uuid(),
        'id_admin_empresa' => rand(1, 50),
        'nome'             => nomeCompletoAleatorio(),
        'email'            => emailAleatorio(),
        'mensagem'         => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.
         Suscipit, assumenda! Accusantium inventore ad velit iusto dicta mollitia minus officiis saepe!',
        'telefone'         => telefoneAleatorio(),
        'status'           => valorAleatorio($listaStatus)
    ];
}

return $seeds;
