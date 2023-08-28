<?php

return [
    'titulo'     => 'Notícias',
    'buscar'     => true,
    'filtrar'    => true,
    'ordem'      => true,
    'visualizar' => false,
    'add'        => true,
    'editar'     => true,
    'deletar'    => true,
    'download'   => false,
    'historico'  => false,
    'api'        => [
        'scope' => 'publicacao_noticia',
        'uri'   => '/publicacao-noticia'
    ]
];
