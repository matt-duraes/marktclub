<?php

return [
    'titulo'     => 'Plano de saúde',
    'buscar'     => false,
    'filtrar'    => false,
    'ordem'      => true,
    'visualizar' => false,
    'add'        => true,
    'editar'     => true,
    'deletar'    => true,
    'download'   => false,
    'historico'  => false,
    'api'        => [
        'scope' => 'saude_convenio',
        'uri'   => '/saude-convenio'
    ]
];
