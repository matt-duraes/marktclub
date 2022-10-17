<?php

return [
    'html' => [
        [
            'coluna' => 'auto',
            [
                'titulo' => 'Dados do cliente',
                'lista' => [
                    'input, name:titulo, label:Título do álbum, obrigatorio:1',
                    'editor, name:texto, label:Descrição do álbum, obrigatorio:1',
                    [
                        'id' => 'bloco_publicacao',
                        'data, name:data_publicacao, label: Data de publicação, obrigatorio:1',
                        'data, name:data_remocao, label: Data de remoção',
                    ],
                    [
                        'id' => 'bloco_tamanho_fixo',
                        'select, name:tipo, id: bloco_input_tipo, label:Qual tipo de álbum?, obrigatorio:1, change:mudarTipoAlbum, lista: ' . painelSelectConfig([
                            '' => 'Escolha uma opção',
                            1 => 'Tamanho real',
                            2 => 'Tamanho fixo',
                            3 => 'Largura máxima',
                            4 => 'Altura máxima',
                        ]),
                        ['id' => 'bloco_linha_tipo'],
                        'numero, name:width, label:Largura, placeholder:Largura em pixel, obrigatorio:1, id:bloco_input_width, maximo:4',
                        'numero, name:height, label:Altura, placeholder:Altura em pixel, obrigatorio:1, id:bloco_input_height, maximo:4'
                    ],
                    'painelCheckbox, titulo:Tipo de extensões' => [
                        'checkbox, name:extensao[], label: JPG, value:jpg',
                        'checkbox, name:extensao[], label: PNG, value:png',
                        'checkbox, name:extensao[], label: GIF, value:gif',
                        'checkbox, name:extensao[], label: SVG, value:svg',
                    ],
                    'switch, name:status, label:Ativar o álbum?'
                ]
            ]
        ]
    ],
    'js' => 'painel_album_Views_album_add.js',
    'css' => 'painel_album_Views_album_add.css',
    'link' => LINK . '/album'
];
