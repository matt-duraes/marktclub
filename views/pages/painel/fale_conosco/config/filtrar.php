<?php

use Painel\FaleConosco\Models\FaleConoscoHelper;

$status = array_merge(['' => 'Escolha uma opção'], FaleConoscoHelper::STATUS_VALOR_TEXTO);
return [
    'input' => [
        'input, name:nome, label:Nome, placeholder:Digite um nome',
        'email, name:email, label:E-mail, placeholder:Digite um e-mail',
        'telefone, name:telefone, label:telefone, placeholder:Digite um telefone',
        'select, name:status, label:Status, lista:' . painelSelectConfig($status)
    ],
    'nome' => [
        'nome' => 'Nome',
        'email' => 'E-mail',
        'telefone' => 'Telefone',
        'status' => 'Status'
    ],
    'valor' => [
        'status' => FaleConoscoHelper::STATUS_VALOR_TEXTO
    ]
];
