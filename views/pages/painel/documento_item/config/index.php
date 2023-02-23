<?php

return [
    'ordem' => [
        'padrao' => 'nome-a-z',
        'lista' => [
            base64Encode('mais-novo', true) => ['Mais novos', 'id', 'DESC'],
            base64Encode('mais-velho', true) => ['Mais antigos', 'id', 'ASC'],
            base64Encode('titulo-a-z', true) => ['Título A-Z', 'titulo', 'ASC'],
            base64Encode('titulo-z-a', true) => ['Título Z-A', 'titulo', 'DESC']
        ]
    ],
    'campo' => ['uuid', 'titulo', 'data_criacao', 'status'],
    'grade' => [
        [
            'nome' => 'Título',
            'tipo' => 'grande',
            'campo' => '->titulo'
        ],
        [
            'nome' => 'Criado em',
            'tipo' => 'pequeno',
            'campo' => '->data'
        ],
        [
            'nome' => 'Status',
            'tipo' => 'status',
            'campo' => '->status'
        ]
    ]
];
