<?php

use App\Classes\PontoCvs\Helper;

return [
    'titulo' => 'Ponto+Ação',
    'buscar' => false,
    'filtrar' => true,
    'add' => false,
    'ordem' => true,
    'visualizar' => true,
    'editar' => true,
    'deletar' => false,
    'historico' => true,
    'api' => [
        'scope' => 'ponto_cvs',
        'uri' => '/ponto-cvs',
        'criptografar' => Helper::CRIPTOGRAFAR
    ]
];
