<?php

use App\Classes\ComunicacaoContato\Status;

$seeds = [];
for ($i = 0; $i < env('QTD_SEEDS', 500); $i++) {
    $seeds[] = [
        'uuid'             => uuid(),
        'id_admin_empresa' => valorAleatorio([
            1, 2, 4, 82, 153, 198, 223, 229,
            1967, 1968, 1969, 1970, 1971
        ]),
        'nome'             => nomeCompletoAleatorio(),
        'email'            => emailAleatorio(),
        'mensagem'         => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.
         Suscipit, assumenda! Accusantium inventore ad velit iusto dicta mollitia minus officiis saepe!',
        'telefone'         => telefoneAleatorio(),
        'status'           => valorAleatorio((new Status())->listarNumero())
    ];
}

return $seeds;
