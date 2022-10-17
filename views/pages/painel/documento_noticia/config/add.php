<?php

$Tag = new \Painel\DataPolicy\Models\DataPolicy;
$listaTag = $Tag->pegarSelect();
$inputTag = [];
foreach ($listaTag as $ind => $val) {
    $inputTag[] = 'checkbox, name:tag[], label:' . $val . ', value:' . $ind;
}

return [
    'html' => [
        [
            [
                'titulo' => 'Dados do documento',
                'lista' => [
                    'input, name:titulo, label:Título, obrigatorio:1',
                    'input, name:fonte_nome, label:Nome da fonte',
                    'url, name:fonte_link, label:Link da fonte',
                ]
            ]
        ],
        [
            [
                'titulo' => 'Texto do documento',
                'lista' => [
                    'editor, name:texto, obrigatorio:1, diretorioImagem: e53ae4e0-7b33-4988-99ad-50433a29b544, diretorioArquivo: 2d978fba-4bd2-4af7-80bf-ebb94d9ac991, placeholder:Digite um texto para o documento',
                ]
            ]
        ],
        [
            [
                'titulo' => 'Tags',
                'lista' => [
                    'painelCheckbox, mais:1, margin:' => $inputTag
                ]
            ]
        ],
        [
            [
                'titulo' => 'Publicação do documento',
                'lista' => [
                    'data, name:data_publicacao, label: Data de publicação, obrigatorio:1',
                    'switch, name:status, label: Ativar documento'
                ]
            ]
        ],
    ],
];
