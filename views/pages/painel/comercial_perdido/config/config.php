<?php

use App\Classes\ComercialEmpresa\Helper;

return [
    'titulo'     => 'Negócios Perdidos',
    'buscar'     => false,
    'filtrar'    => false,
    'ordem'      => true,
    'visualizar' => true,
    'add'        => false,
    'editar'     => true,
    'deletar'    => false,
    'download'   => false,
    'historico'  => true,
    'api'        => [
        'scope'        => 'comercial_empresa',
        'uri'          => '/comercial-empresa',
        'criptografar' => Helper::CRIPTOGRAFAR
    ]
];
