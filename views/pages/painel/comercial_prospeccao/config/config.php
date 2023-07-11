<?php

use App\Classes\ComercialEmpresa\Helper;

return [
    'titulo'     => 'Prospecção',
    'buscar'     => false,
    'filtrar'    => false,
    'ordem'      => false,
    'visualizar' => false,
    'add'        => true,
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
