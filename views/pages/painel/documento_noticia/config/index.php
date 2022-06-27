<?php

return [
    'drag' => false,
    'ordem' => [
        'padrao' => 'nome-a-z',
        'lista' => [
            base64Encode('mais-novo', 'ordem') => ['Mais novos', 'id', 'DESC'],
            base64Encode('mais-velho', 'ordem') => ['Mais antigos', 'id', 'ASC'],
            base64Encode('titulo-a-z', 'ordem') => ['Título A-Z', 'titulo', 'ASC'],
            base64Encode('titulo-z-a', 'ordem') => ['Título Z-A', 'titulo', 'DESC'],
            base64Encode('data-asc', 'ordem') => ['Data publicação crescente', 'data_publicacao', 'ASC'],
            base64Encode('data-desc', 'ordem') => ['Data publicação decrescente', 'data_publicacao', 'DESC'],
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
