<?php

use App\Classes\Geral\Status;

return [
    [
        'uuid'             => '10dbac1a-bac5-4de0-aba7-f74803aabbc3',
        'id_admin_empresa' => 1,
        'titulo'           => 'lorem ipson asda diow milop - ' . numeroAleatorio(1, 10),
        'imagem'           => 'https://arquivo.marktclub.com.br/parceiro/06802a47edc6d54046ff4ac2e9394c20.png',
        'link'             => 'https://via.placeholder.cpm/1920x1080?text=MARKTCLUB',
        'target'           => '_self',
        'tipo'             => 1,
        'status'           => valorAleatorio((new Status())->listarNumero())
    ],
    [
        'uuid'             => uuid(),
        'id_admin_empresa' => 1,
        'titulo'           => 'lorem ipson asda diow milop - ' . numeroAleatorio(1, 10),
        'imagem'           => 'https://arquivo.marktclub.com.br/parceiro/06802a47edc6d54046ff4ac2e9394c20.png',
        'link'             => 'https://via.placeholder.cpm/1920x1080?text=MARKTCLUB',
        'target'           => '_blank',
        'tipo'             => 1,
        'status'           => valorAleatorio((new Status())->listarNumero())
    ],
    [
        'uuid'             => uuid(),
        'id_admin_empresa' => 1,
        'titulo'           => 'lorem ipson asda diow milop - ' . numeroAleatorio(1, 10),
        'imagem'           => 'https://arquivo.marktclub.com.br/parceiro/28666023495b4b1dc74bec19143d8bf3.png',
        'link'             => 'https://via.placeholder.cpm/1920x1080?text=MARKTCLUB',
        'target'           => '_self',
        'tipo'             => 1,
        'status'           => valorAleatorio((new Status())->listarNumero())
    ],
    [
        'uuid'             => uuid(),
        'id_admin_empresa' => 1,
        'titulo'           => 'lorem ipson asda diow milop - ' . numeroAleatorio(1, 10),
        'imagem'           => 'https://arquivo.marktclub.com.br/parceiro/06802a47edc6d54046ff4ac2e9394c20.png',
        'link'             => 'https://via.placeholder.cpm/1920x1080?text=MARKTCLUB',
        'target'           => '_blank',
        'tipo'             => 1,
        'status'           => valorAleatorio((new Status())->listarNumero())
    ],
    [
        'uuid'             => uuid(),
        'id_admin_empresa' => 1,
        'titulo'           => 'lorem ipson asda diow milop - ' . numeroAleatorio(1, 10),
        'imagem'           => 'https://arquivo.marktclub.com.br/parceiro/06802a47edc6d54046ff4ac2e9394c20.png',
        'link'             => 'https://via.placeholder.cpm/1920x1080?text=MARKTCLUB',
        'target'           => '_blank',
        'tipo'             => 1,
        'status'           => valorAleatorio((new Status())->listarNumero())
    ]
];
