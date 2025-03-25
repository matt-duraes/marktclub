<?php

use App\Classes\ComercialEmpresa\Helper;

return [
    'titulo'     => 'Prospecções s/Responsável',
    'buscar'     => true,
    'filtrar'    => true,
    'ordem'      => true,
    'visualizar' => false,
    'add'        => false,
    'editar'     => true,
    'deletar'    => false,
    'download'   => false,
    'historico'  => false,
    'api'        => [
        'scope'        => 'comercial_empresa',
        'uri'          => '/comercial-empresa',
        'criptografar' => Helper::CRIPTOGRAFAR
    ]
];
