<?php

use App\Classes\ComercialEmpresa\Helper;

return [
    'titulo' => 'Comercial',
    'buscar' => true,
    'filtrar' => true,
    'ordem' => true,
    'visualizar' => true,
    'add' => true,
    'editar' => true,
    'deletar' => true,
    'download' => true,
    'historico' => true,
    'api' => [
        'scope' => 'comercial_empresa',
        'uri' => '/comercial-empresa',
        'criptografar' => Helper::CRIPTOGRAFAR
    ]
];
