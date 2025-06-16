<?php

return [
    'titulo'     => 'Código de Primeiro Acesso (Usuário)',
    'buscar'     => false,
    'filtrar'    => true,
    'ordem'      => true,
    'visualizar' => false,
    'add'        => true,
    'editar'     => true,
    'deletar'    => true,
    'download'   => false,
    'historico'  => false,
    'api'        => [
        'scope' => 'usuario_cliente_codigo',
        'uri'   => '/usuario-cliente-codigo',
    ]
];
