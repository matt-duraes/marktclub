<?php

return [
    'html' => [
        [
            'coluna' => 'auto',
            [
                'titulo' => 'Dados do item',
                'lista' => [
                    'input, name:titulo, label:Título, obrigatorio:1',
                    'textarea, name:texto, label:Descrição, obrigatorio:1',
                    'switch, name:status, label: Ativar item:'
                ]
            ]
        ],
    ],
];
