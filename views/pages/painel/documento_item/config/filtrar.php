<?php

$statusLista = [
    1 => 'Liberado',
    0 => 'Inativo'
];

return [
    'input' => [
        'input, name:titulo, label:Título, placeholder:Digite um título',
        'select, name:status, label: Status, lista: ' . painelSelectConfig($statusLista)
    ],
    'nome' => [
        'titulo' => 'Título',
        'status' => 'Status'
    ],
    'valor' => [
        'status' => $statusLista
    ]
];
