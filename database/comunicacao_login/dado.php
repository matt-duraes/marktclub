<?php

return [
    [
        'uuid'            => uuid(),
        'titulo'          => 'Comunicado 1',
        'id_admin_empresa'=> '[1]',
        'padrao'          => 1,
        'arquivo_1'       => uuid(),
        'arquivo_2'       => '',
        'arquivo_3'       => '',
        'data_inicio'     => dataPassadaAleatorio(),
        'data_fim'        => dataFuturaAleatorio(),
        'status'          => 1
    ]
];
