<?php

return [
    'drag' => false,
    'ordem' => [
        'padrao' => 'nome-a-z',
        'lista' => [
            base64Encode('mais-novo', 'ordem') => ['Mais novos', 'id', 'DESC'],
            base64Encode('mais-velho', 'ordem') => ['Mais antigos', 'id', 'ASC'],
            base64Encode('nome-asc', 'ordem') => ['Nome crescente', 'nome', 'ASC'],
            base64Encode('nome-desc', 'ordem') => ['Nome decrescente', 'nome', 'DESC'],
        ]
    ],
    'campo' => ['uuid', 'nome', 'data_criacao', 'status'],
    'grade' => [
        [
            'nome' => 'Título',
            'tipo' => 'grande',
            'campo' => '->titulo'
        ],
        [
            'nome' => 'Mês',
            'tipo' => 'pequeno',
            'campo' => '->data->mes'
        ],
        [
            'nome' => 'Ano',
            'tipo' => 'pequeno',
            'campo' => '->data->ano'
        ],
        [
            'nome' => 'Status',
            'tipo' => 'status',
            'campo' => '->status'
        ]
    ]
];
