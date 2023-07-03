<?php

use App\Classes\Publicidade\Tipo;

return [
    [
        'uuid'     => uuid(),
        'empresa'  => numeroAleatorio(1, 10),
        'titulo'   => 'lorem ipson asda diow milop - ' . numeroAleatorio(1, 10),
        'imagem'   => 'https://via.placeholder.cpm/' . valorAleatorio([1000, 2000, 3000, 4000, 5000]),
        'link'     => 'https://via.placeholder.cpm/1920x1080?text=MARKTCLUB',
        'target'   => '_self',
        'parceiro' => '',
        'ordem'    => 1,
        'tipo'     => valorAleatorio((new Tipo())->listarNumero()),
        'status'   => 4
    ],
    [
        'uuid'     => uuid(),
        'empresa'  => numeroAleatorio(1, 10),
        'titulo'   => 'lorem ipson asda diow milop - ' . numeroAleatorio(1, 10),
        'imagem'   => 'https://via.placeholder.cpm/' . valorAleatorio([1000, 2000, 3000, 4000, 5000]),
        'link'     => 'https://via.placeholder.cpm/1920x1080?text=MARKTCLUB',
        'target'   => '_blank',
        'parceiro' => '',
        'ordem'    => 1,
        'tipo'     => valorAleatorio((new Tipo())->listarNumero()),
        'status'   => 4
    ],
    [
        'uuid'     => uuid(),
        'empresa'  => numeroAleatorio(1, 10),
        'titulo'   => 'lorem ipson asda diow milop - ' . numeroAleatorio(1, 10),
        'imagem'   => 'https://via.placeholder.cpm/' . valorAleatorio([1000, 2000, 3000, 4000, 5000]),
        'link'     => 'https://via.placeholder.cpm/1920x1080?text=MARKTCLUB',
        'target'   => '_self',
        'parceiro' => '',
        'ordem'    => 1,
        'tipo'     => valorAleatorio((new Tipo())->listarNumero()),
        'status'   => 4
    ],
    [
        'uuid'     => uuid(),
        'empresa'  => numeroAleatorio(1, 10),
        'titulo'   => 'lorem ipson asda diow milop - ' . numeroAleatorio(1, 10),
        'imagem'   => 'https://via.placeholder.cpm/' . valorAleatorio([1000, 2000, 3000, 4000, 5000]),
        'link'     => 'https://via.placeholder.cpm/1920x1080?text=MARKTCLUB',
        'target'   => '_blank',
        'parceiro' => '',
        'ordem'    => 1,
        'tipo'     => valorAleatorio((new Tipo())->listarNumero()),
        'status'   => 4
    ]
];
