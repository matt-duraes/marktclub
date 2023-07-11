<?php

use App\Classes\UsuarioDependente\Helper;

return [
    'titulo'     => 'Dependente',
    'buscar'     => false,
    'filtrar'    => false,
    'ordem'      => false,
    'visualizar' => false,
    'download'   => false,
    'add'        => true,
    'editar'     => true,
    'deletar'    => true,
    'historico'  => false,
    'api'        => [
        'scope'        => 'usuario_dependente',
        'uri'          => '/usuario-dependente',
        'criptografar' => Helper::CRIPTOGRAFAR
    ]
];
