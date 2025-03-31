<?php

use App\Classes\ComercialEmpresa\Helper;

return [
    'titulo'     => 'Contratos',
    'buscar'     => true,
    'filtrar'    => true,
    'ordem'      => true,
    'visualizar' => true,
    'download'   => true,
    'add'        => false,
    'editar'     => false,
    'deletar'    => false,
    'historico'  => false,
    'api'        => [
        'scope'        => 'comercial_empresa',
        'uri'          => '/comercial-empresa',
        'criptografar' => Helper::CRIPTOGRAFAR
    ]
];
