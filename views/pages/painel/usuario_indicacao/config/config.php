<?php

use App\Classes\UsuarioIndicacao\Helper;

return [
    'titulo' => 'Indicação',
    'buscar' => true,
    'filtrar' => true,
    'ordem' => true,
    'visualizar' => true,
    'add' => false,
    'editar' => false,
    'deletar' => false,
    'historico' => true,
    'api' => [
        'scope' => 'usuario_indicacao',
        'uri' => '/usuario-indicacao',
        'criptografar' => Helper::CRIPTOGRAFAR
    ]
];
