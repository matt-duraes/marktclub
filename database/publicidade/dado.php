<?php

use App\Classes\Publicidade\Tipo;
use App\Classes\StatusGeral\Status;

return [
    [
        'uuid'    => '10dbac1a-bac5-4de0-aba7-f74803aabbc3',
        'empresa' => 1,
        'titulo'  => 'lorem ipson asda diow milop - ' . numeroAleatorio(1, 10),
        'imagem'  => 'https://via.placeholder.cpm/' . valorAleatorio([1000, 2000, 3000, 4000, 5000]),
        'link'    => 'https://via.placeholder.cpm/1920x1080?text=MARKTCLUB',
        'target'  => '_self',
        'tipo'    => valorAleatorio((new Tipo())->listarNumero()),
        'status'  => valorAleatorio((new Status())->listarNumero())
    ],
    [
        'uuid'    => uuid(),
        'empresa' => 1,
        'titulo'  => 'lorem ipson asda diow milop - ' . numeroAleatorio(1, 10),
        'imagem'  => 'https://via.placeholder.cpm/' . valorAleatorio([1000, 2000, 3000, 4000, 5000]),
        'link'    => 'https://via.placeholder.cpm/1920x1080?text=MARKTCLUB',
        'target'  => '_blank',
        'tipo'    => valorAleatorio((new Tipo())->listarNumero()),
        'status'  => valorAleatorio((new Status())->listarNumero())
    ],
    [
        'uuid'    => uuid(),
        'empresa' => 1,
        'titulo'  => 'lorem ipson asda diow milop - ' . numeroAleatorio(1, 10),
        'imagem'  => 'https://via.placeholder.cpm/' . valorAleatorio([1000, 2000, 3000, 4000, 5000]),
        'link'    => 'https://via.placeholder.cpm/1920x1080?text=MARKTCLUB',
        'target'  => '_self',
        'tipo'    => valorAleatorio((new Tipo())->listarNumero()),
        'status'  => valorAleatorio((new Status())->listarNumero())
    ],
    [
        'uuid'    => uuid(),
        'empresa' => 1,
        'titulo'  => 'lorem ipson asda diow milop - ' . numeroAleatorio(1, 10),
        'imagem'  => 'https://via.placeholder.cpm/' . valorAleatorio([1000, 2000, 3000, 4000, 5000]),
        'link'    => 'https://via.placeholder.cpm/1920x1080?text=MARKTCLUB',
        'target'  => '_blank',
        'tipo'    => valorAleatorio((new Tipo())->listarNumero()),
        'status'  => valorAleatorio((new Status())->listarNumero())
    ]
];
