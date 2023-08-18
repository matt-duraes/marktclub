<?php

use App\Classes\ParceiroIndicacao\Status;

return [
    [
        'uuid'              => uuid(),
        'nome'              => nomeCompletoAleatorio(),
        'telefone'          => telefoneAleatorio(),
        'email'             => emailAleatorio(),
        'mensagem'          => 'Mensagem de Exemplo',
        'status'            => valorAleatorio((new Status())->listarNumero())
    ]
];
