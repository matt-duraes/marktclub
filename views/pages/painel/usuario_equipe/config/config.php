<?php

use App\Classes\UsuarioEquipe\Helper;

return [
    'titulo' => 'Equipe',
    'buscar' => true,
    'filtrar' => true,
    'add' => true,
    'editar' => true,
    'deletar' => true,
    'ordem' => true,
    'historico' => true,
    'api' => [
        'scope' => 'usuario_equipe',
        'uri' => '/usuario-equipe',
        'criptografar' => Helper::CRIPTOGRAFAR
    ]
];
