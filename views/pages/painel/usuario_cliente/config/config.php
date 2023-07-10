<?php

use App\Classes\UsuarioCliente\Helper;

return [
    'titulo'     => 'Usuários',
    'buscar'     => true,
    'filtrar'    => true,
    'ordem'      => true,
    'visualizar' => true,
    'download'   => true,
    'add'        => true,
    'editar'     => true,
    'deletar'    => true,
    'historico'  => true,
    'api'        => [
        'scope'        => 'usuario_cliente',
        'uri'          => '/usuario-cliente',
        'criptografar' => Helper::CRIPTOGRAFAR
    ]
];
