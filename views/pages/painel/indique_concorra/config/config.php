<?php

use App\Classes\ComercialEmpresa\Helper;

return [
    'titulo'     => 'Minhas indicações',
    'buscar'     => false,
    'filtrar'    => false,
    'ordem'      => false,
    'index'      => '*',
    'visualizar' => false,
    'download'   => false,
    'add'        => '*',
    'editar'     => false,
    'deletar'    => false,
    'historico'  => false,
    'api'        => [
        'scope'        => 'comercial_empresa',
        'uri'          => '/comercial-empresa',
        'criptografar' => Helper::CRIPTOGRAFAR
    ]
];
