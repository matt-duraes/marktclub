<?php

return [
    [
        'uuid'               => uuid(),
        'id_admin_empresa'   => 1,
        'id_usuario_cliente' => 3,
        'voucher'            => 'https://google.com',
        'data_vencimento'    => dataFuturaAleatorio() . ' 00:00:00',
        'status'             => 1
    ]
];
