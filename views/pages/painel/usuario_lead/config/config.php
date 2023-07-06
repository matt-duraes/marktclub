<?php

use App\Classes\UsuarioLead\Helper;

return [
    'titulo'     => 'Lead',
    'buscar'     => true,
    'filtrar'    => true,
    'add'        => false,
    'visualizar' => true,
    'editar'     => false,
    'deletar'    => true,
    'historico'  => true,
    'api'        => [
        'scope'        => 'usuario_lead',
        'uri'          => '/usuario-lead',
        'criptografar' => Helper::CRIPTOGRAFAR
    ]
];
