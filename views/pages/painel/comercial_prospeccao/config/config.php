<?php

use App\Classes\ComercialEmpresa\Helper;

return [
    'titulo'     => 'Prospecção',
    'buscar'     => false,
    'filtrar'    => false,
    'ordem'      => false,
    'visualizar' => true,
    'add'        => true,
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
