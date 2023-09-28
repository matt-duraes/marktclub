<?php

use App\Classes\ComercialEmpresa\Helper;

return [
    'titulo'     => 'Negócios Perdidos',
    'buscar'     => false,
    'filtrar'    => false,
    'ordem'      => true,
    'visualizar' => true,
    'add'        => false,
    'editar'     => false,
    'deletar'    => false,
    'download'   => false,
    'historico'  => false,
    'api'        => [
        'scope'        => 'comercial_empresa',
        'uri'          => '/comercial-empresa',
        'criptografar' => Helper::CRIPTOGRAFAR
    ]
];
