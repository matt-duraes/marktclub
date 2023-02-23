<?php

return [
    'drag' => false,
    'ordem' => [
        'padrao' => 'nome-a-z',
        'lista' => [
            base64Encode('mais-novo', true) => ['Mais novos', 'id', 'DESC'],
            base64Encode('mais-velho', true) => ['Mais antigos', 'id', 'ASC'],
            base64Encode('titulo-a-z', true) => ['Título A-Z', 'titulo', 'ASC'],
            base64Encode('titulo-z-a', true) => ['Título Z-A', 'titulo', 'DESC'],
            base64Encode('data-asc', true) => ['Data publicação crescente', 'data_publicacao', 'ASC'],
            base64Encode('data-desc', true) => ['Data publicação decrescente', 'data_publicacao', 'DESC'],
        ]
    ],
    'campo' => ['uuid', 'titulo', 'data_publicacao', 'status'],
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
