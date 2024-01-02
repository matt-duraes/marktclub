<?php

return [
    [
        'uuid'             => uuid(),
        'id_admin_empresa' => jsonEncode([1]),
        'titulo_painel'    => 'Titulo de teste 1',
        'slug'             => 'popup-01-teste',
        'imagem'           => null,
        'titulo'           => 'Popup de teste 01',
        'texto'            => 'Texto do popup de teste 01',
        'regulamento'      => 'Regulamento do popup de teste 01',
        'data_inicio'      => dataPassadaAleatorio(),
        'data_final'       => dataFuturaAleatorio(),
        'atualizar_dado'   => null,
        'botao_texto'      => 'Abrir Google',
        'botao_link'       => 'https://google.com',
        'botao_target'     => 2,
        'status'           => 1
    ],
    [
        'uuid'             => uuid(),
        'id_admin_empresa' => jsonEncode([1]),
        'titulo_painel'    => 'Titulo de teste 2',
        'slug'             => 'popup-02-teste',
        'imagem'           => null,
        'titulo'           => 'Popup de teste 02',
        'texto'            => 'Texto do popup de teste 02',
        'regulamento'      => 'Regulamento do popup de teste 02',
        'data_inicio'      => dataPassadaAleatorio(),
        'data_final'       => dataFuturaAleatorio(),
        'atualizar_dado'   => null,
        'botao_texto'      => 'Abrir Google',
        'botao_link'       => 'https://google.com',
        'botao_target'     => 2,
        'status'           => 1
    ]
];
