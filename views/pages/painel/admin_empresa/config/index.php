<?php

return [
    'drag' => false,
    'ordem' => [
        'padrao' => 'nome-a-z',
        'lista' => [
            base64Encode('mais-novo', 'ordem') => ['Mais novos', 'id', 'DESC'],
            base64Encode('mais-velho', 'ordem') => ['Mais antigos', 'id', 'ASC'],
            base64Encode('nome-a-z', 'ordem') => ['Nome A-Z', 'nome_fantasia', 'ASC'],
            base64Encode('nome-z-a', 'ordem') => ['Nome Z-A', 'nome_fantasia', 'DESC']
        ]
    ],
    'campo' => ['uuid', 'nome_fantasia', 'responsavel_email', 'documento_cnpj', 'data_criacao', 'status'],
    'grade' => [
        [
            'nome' => 'Nome',
            'tipo' => 'grande',
            'campo' => '->nome'
        ],
        [
            'nome' => 'CNPJ',
            'tipo' => 'pequeno',
            'campo' => '->cnpj'
        ],
        [
            'nome' => 'E-mail',
            'tipo' => 'normal',
            'campo' => '->email'
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
