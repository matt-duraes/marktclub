<?php

return [
    'drag'  => false,
    'ordem' => [
        'padrao' => 'nome-a-z',
        'lista'  => [
            base64Encode('mais-novo', true)  => ['Mais novos', 'id', 'DESC'],
            base64Encode('mais-velho', true) => ['Mais antigos', 'id', 'ASC'],
            base64Encode('nome-a-z', true)   => ['Nome A-Z', 'nome', 'ASC'],
            base64Encode('nome-z-a', true)   => ['Nome Z-A', 'nome', 'DESC'],
        ]
    ],
    'campo' => ['uuid', 'nome', 'email', 'data_criacao', 'status'],
    'grade' => [
        [
            'nome'  => 'Nome',
            'tipo'  => 'grande',
            'campo' => '->nome'
        ],
        [
            'nome'  => 'E-mail',
            'tipo'  => 'normal',
            'campo' => '->email'
        ],
        [
            'nome'  => 'Data',
            'tipo'  => 'pequeno',
            'campo' => '->data'
        ],
        [
            'nome'  => 'Status',
            'tipo'  => 'status',
            'campo' => '->status'
        ]
    ]
];
