<?php

return [
    [
        'uuid'             => uuid(),
        'id_admin_empresa' => jsonEncode([1]),
        'titulo_painel'    => 'Popup para titular',
        'usuario_tipo'     => jsonEncode([1]),
        'slug'             => 'popup-para-titular',
        'imagem'           => null,
        'titulo'           => 'Popup apenas para titular',
        'texto'            => 'Texto do popup de teste 01',
        'regulamento'      => 'Regulamento do popup de teste 01',
        'data_inicio'      => dataPassadaAleatorio(),
        'data_final'       => dataFuturaAleatorio(),
        'atualizar_dado'   => null,
        'botao_texto'      => 'Abrir Google',
        'botao_link'       => 'https://google.com',
        'botao_target'     => 2,
        'status'           => 1
    ]
];
