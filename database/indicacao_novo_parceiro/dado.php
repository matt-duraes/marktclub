<?php

use App\Classes\IndicacaoNovoParceiro\Status;

return [
    [
        'uuid'              => uuid(),
        'nome_indicado'     => nomeAleatorio(),
        'telefone_indicado' => telefoneAleatorio(),
        'email_indicado'    => emailAleatorio(),
        'mensagem'          => 'Mensagem de Exemplo',
        'status'            => valorAleatorio((new Status())->listarNumero())
    ]
];
