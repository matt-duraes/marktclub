<?php

return [
    'titulo'     => 'Álbuns',
    'buscar'     => true,
    'filtrar'    => true,
    'ordem'      => true,
    'visualizar' => false,
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
