<?php

return [
    'titulo'     => 'Solicitações de Saque',
    'buscar'     => false,
    'filtrar'    => true,
    'ordem'      => true,
    'visualizar' => true,
    'add'        => false,
    'editar'     => false,
    'deletar'    => true,
    'download'   => false,
    'historico'  => true,
    'api'        => [
        'scope' => 'silium_deposito',
        'uri'   => '/silium-deposito'
    ]
];
