<?php

use App\Classes\ComercialEmpresa\Helper;

return [
    'titulo'     => 'Minhas indicações',
    'buscar'     => false,
    'filtrar'    => false,
    'ordem'      => false,
    'index'      => true,
    'visualizar' => false,
    'download'   => false,
    'add'        => true,
    'editar'     => false,
    'deletar'    => false,
    'historico'  => false,
    'api'        => [
        'scope'        => 'comercial_empresa',
        'uri'          => '/comercial-empresa',
        'criptografar' => Helper::CRIPTOGRAFAR
    ]
];
