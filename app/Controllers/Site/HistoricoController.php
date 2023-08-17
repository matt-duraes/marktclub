<?php

namespace App\Controllers\Site;

use Http\Response;
use Controller\Controller;

final class HistoricoController extends Controller
{
    public function postBuscar(): Response
    {
        return mensagemSucesso([
            [
                'id'     => 1,
                'titulo' => 'Parceiro 1',
                'logo'   => 'https://arquivo.marktclub.com.br/parceiro/b9994388644c6d04bea5495a3ed1fe72.png',
                'imagem' => [
                    [
                        'id'     => 1,
                        'link'   => 'https://google.com',
                        'imagem' => 'https://arquivo.marktclub.com.br/publicidade/0ae0826f5dd931e49c7796aa24bd618e.png'
                    ],
                    [
                        'id'     => 2,
                        'link'   => 'https://google.com',
                        'imagem' => 'https://arquivo.marktclub.com.br/publicidade/8863ad96a201ded0de113aa224420ed5.jpg'
                    ],
                ]
            ],
            [
                'id'     => 2,
                'titulo' => 'Parceiro 2',
                'logo'   => 'https://arquivo.marktclub.com.br/parceiro/2a9eafaf7ce7401c7bd65357899bb5cb.png',
                'imagem' => [
                    [
                        'id'     => 3,
                        'link'   => 'https://google.com',
                        'imagem' => 'https://arquivo.marktclub.com.br/publicidade/575e638d5ad7a19ec4c29980080854a3.png'
                    ],
                    [
                        'id'     => 4,
                        'link'   => 'https://google.com',
                        'imagem' => 'https://arquivo.marktclub.com.br/publicidade/4d551cb9b66fefca6ed1757f23d24e38.png'
                    ],
                    [
                        'id'     => 5,
                        'link'   => 'https://google.com',
                        'imagem' => 'https://arquivo.marktclub.com.br/publicidade/2b3f3ae86a18370c1882ff2836958214.png'
                    ],
                ]
            ],
        ]);
    }
}
