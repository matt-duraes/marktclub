<?php

return [
    [
        'id_admin_empresa'   => 1,
        'titulo'             => 'Live de teste',
        'titulo_interno'     => 'Live de teste',
        'texto'              => '<p>Descrição da live de teste</p>',
        'link'               => 'https://meet.google.com',
        'incorporar' => 1,
        'botao_texto' => 'Ao vivo',
        'permissao_restrita' => 1,
        'permissao_site'     => 1,
        'link_restrito'      => 1,
        'data_inicio'        => agora(),
        'data_final'         => dataAdicionar(agora(), 2, 'dias', 'Y-m-d H:i:s'),
        'status'             => 1,
    ]
];
