<?php

namespace App\Models\Site;

final class BannerModel
{
    public function index()
    {
        return (object)[
        'desktop' => [
            (object)[
                'imagem' => 'https://arquivo.marktclub.com.br/publicidade/873354e55041317af1d891c2f81ed355.png',
                'link' => route('loja.detalhe') . '/loja'
            ],
            (object)[
                'imagem' => 'https://arquivo.marktclub.com.br/publicidade/a9a4e3f337b4dfdcbc4c83dc29c524c6.jpg',
                'link' => route('loja.detalhe') . '/loja'
            ],
        ],
        'mobile' => [
            (object)[
                'imagem' => 'https://arquivo.marktclub.com.br/publicidade/873354e55041317af1d891c2f81ed355.png',
                'link' => route('loja.detalhe') . '/loja'
            ],
            (object)[
                'imagem' => 'https://arquivo.marktclub.com.br/publicidade/a9a4e3f337b4dfdcbc4c83dc29c524c6.jpg',
                'link' => route('loja.detalhe') . '/loja'
            ],
        ]
        ];
    }

    public function loja()
    {
        return $this->index();
    }
    public function automovel()
    {
        return $this->index();
    }

    public function turismo()
    {
        return (object)[
        'desktop' => [
            (object)[
                'imagem' => LINK_PADRAO . '/images/site/turismo_123_desktop.png',
                'link' => ''
            ]
        ],
        'mobile' => [
            (object)[
                'imagem' => LINK_PADRAO . '/images/site/turismo_123_mobile.png',
                'link' => ''
            ]
        ]
        ];
    }
    public function turismoCarro()
    {
        return (object)[
            'desktop' => [
                (object)[
                    'imagem' => LINK_PADRAO . '/images/site/turismo_carro_desktop.png',
                    'link' => ''
                ]
            ],
            'mobile' => [
                (object)[
                    'imagem' => LINK_PADRAO . '/images/site/turismo_carro_mobile.png',
                    'link' => ''
                ]
            ]
        ];
    }
    public function sicoob()
    {
        return (object)[
            'desktop' => [
                (object)[
                    'imagem' => LINK_PADRAO . '/images/site/credito_sicoob_desktop.jpg',
                    'link' => ''
                ]
            ],
            'mobile' => [
                (object)[
                    'imagem' => LINK_PADRAO . '/images/site/credito_sicoob_mobile.jpg',
                    'link' => ''
                ]
            ]
        ];
    }
    public function alfa()
    {
        return (object)[
            'desktop' => [
                (object)[
                    'imagem' => LINK_PADRAO . '/images/site/credito_alfa_desktop.png',
                    'link' => ''
                ]
            ],
            'mobile' => [
                (object)[
                    'imagem' => LINK_PADRAO . '/images/site/credito_alfa_mobile.png',
                    'link' => ''
                ]
            ]
        ];
    }
}
