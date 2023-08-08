<?php

return [
    [
        'uuid'             => 'd461df84-2acd-41fe-a07a-30c4fe554a0b',
        'id_admin_empresa' => '1',
        'slug'             => strSlug(nomeCompletoAleatorio()),
        'titulo'           => nomeCompletoAleatorio(),
        'subtitulo'        => '',
        'texto'            => '
            Lorem Ipsum is simply dummy text of the printing and typesetting industry.
            Lorem Ipsum has been the industrys standard dummy text ever since the 1500s,
            when an unknown printer took a galley of type and scrambled it to make a type specimen book.
            It has survived not only five centuries, but also the leap into electronic typesetting,
            remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages,
            and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
        ',
        'formulario'       => jsonEncode([
            [
                'label'       => 'teste',
                'type'        => 'text',
                'value'       => '',
                'placeholder' => '',
                'required'    => true,
                'id'          => 'default',
                'class'       => 'default'
            ],
            [
                'label'       => 'teste',
                'type'        => 'text',
                'value'       => '',
                'placeholder' => '',
                'required'    => true,
                'id'          => 'default',
                'class'       => 'default'
            ]
        ]),
        'imagem'           => imagemUsuario(),
        'data_expiracao'  => dataFuturaAleatorio(),
        'status'           => 1
    ],
    [
        'uuid'             => uuid(),
        'id_admin_empresa' => '1',
        'slug'             => strSlug('Pop pi de desconto'),
        'titulo'           => nomeCompletoAleatorio(),
        'subtitulo'        => nomeCompletoAleatorio(),
        'texto'            => nomeCompletoAleatorio(),
        'formulario'       => jsonEncode([
            [
                'label'       => 'teste',
                'type'        => 'text',
                'value'       => '',
                'placeholder' => '',
                'required'    => true,
                'id'          => 'default',
                'class'       => 'default'
            ],
            [
                'label'       => 'teste',
                'type'        => 'text',
                'value'       => '',
                'placeholder' => '',
                'required'    => true,
                'id'          => 'default',
                'class'       => 'default'
            ]
        ]),
        'imagem'           => imagemUsuario(),
        'data_expiracao'  => dataFuturaAleatorio(),
        'status'           => -1
    ],
    [
        'uuid'             => uuid(),
        'id_admin_empresa' => '1',
        'slug'             => strSlug('Pop u de desconto'),
        'titulo'           => nomeCompletoAleatorio(),
        'subtitulo'        => nomeCompletoAleatorio(),
        'texto'            => nomeCompletoAleatorio(),
        'formulario'       => jsonEncode([
            [
                'label'       => 'teste',
                'type'        => 'text',
                'value'       => '',
                'placeholder' => '',
                'required'    => true,
                'id'          => 'default',
                'class'       => 'default'
            ],
            [
                'label'       => 'teste',
                'type'        => 'text',
                'value'       => '',
                'placeholder' => '',
                'required'    => true,
                'id'          => 'default',
                'class'       => 'default'
            ]
        ]),
        'imagem'           => imagemUsuario(),
        'data_expiracao'  => dataFuturaAleatorio(),
        'status'           => 1
    ],
    [
        'uuid'             => uuid(),
        'id_admin_empresa' => '1',
        'slug'             => strSlug('Pop 8 de desconto'),
        'titulo'           => nomeCompletoAleatorio(),
        'subtitulo'        => nomeCompletoAleatorio(),
        'texto'            => nomeCompletoAleatorio(),
        'formulario'       => jsonEncode([
            [
                'label'       => 'teste',
                'type'        => 'text',
                'value'       => '',
                'placeholder' => '',
                'required'    => true,
                'id'          => 'default',
                'class'       => 'default'
            ],
            [
                'label'       => 'teste',
                'type'        => 'text',
                'value'       => '',
                'placeholder' => '',
                'required'    => true,
                'id'          => 'default',
                'class'       => 'default'
            ]
        ]),
        'imagem'           => imagemUsuario(),
        'data_expiracao'  => dataFuturaAleatorio(),
        'status'           => -1
    ],
    [
        'uuid'             => uuid(),
        'id_admin_empresa' => '1',
        'slug'             => strSlug('Pop asd de desconto'),
        'titulo'           => nomeCompletoAleatorio(),
        'subtitulo'        => nomeCompletoAleatorio(),
        'texto'            => nomeCompletoAleatorio(),
        'formulario'       => jsonEncode([
            [
                'label'       => 'teste',
                'type'        => 'text',
                'value'       => '',
                'placeholder' => '',
                'required'    => true,
                'id'          => 'default',
                'class'       => 'default'
            ],
            [
                'label'       => 'teste',
                'type'        => 'text',
                'value'       => '',
                'placeholder' => '',
                'required'    => true,
                'id'          => 'default',
                'class'       => 'default'
            ]
        ]),
        'imagem'           => imagemUsuario(),
        'data_expiracao'  => dataFuturaAleatorio(),
        'status'           => 1
    ]
];
