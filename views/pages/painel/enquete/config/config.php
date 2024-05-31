<?php

return [
    'titulo'     => 'Enquete',
    'buscar'     => false,
    'filtrar'    => false,
    'add'        => true,
    'visualizar' => true,
    'editar'     => true,
    'deletar'    => true,
    'historico'  => true,
    'api'        => [
        'scope'        => 'votacao',
        'uri'          => '/votacao-dado'
    ]
];
