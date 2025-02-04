<?php

return [
    'titulo'     => 'Álbuns',
    'buscar'     => true,
    'filtrar'    => true,
    'ordem'      => true,
    'visualizar' => true,
    'download'   => false,
    'add'        => true,
    'editar'     => true,
    'deletar'    => true,
    'historico'  => false,
    'api'        => [
        'scope'        => 'album_dado',
        'uri'          => '/album-dado',
        'criptografar' => []
    ]
];
