<?php

namespace App\Models\Site;

final class BannerModel
{
    /**
     * @return object
     */
    public function loja(): object
    {
        return $this->index();
    }

    /**
     * @return object
     */
    public function index(): object
    {
        return (object)[
            'desktop' => [
                (object)[
                    'imagem' => 'https://arquivo.marktclub.com.br/publicidade/873354e55041317af1d891c2f81ed355.png',
                    'link'   => route('loja.detalhe') . '/loja'
                ],
                (object)[
                    'imagem' => 'https://arquivo.marktclub.com.br/publicidade/a9a4e3f337b4dfdcbc4c83dc29c524c6.jpg',
                    'link'   => route('loja.detalhe') . '/loja'
                ]
            ],
            'mobile'  => [
                (object)[
                    'imagem' => 'https://arquivo.marktclub.com.br/publicidade/873354e55041317af1d891c2f81ed355.png',
                    'link'   => route('loja.detalhe') . '/loja'
                ],
                (object)[
                    'imagem' => 'https://arquivo.marktclub.com.br/publicidade/a9a4e3f337b4dfdcbc4c83dc29c524c6.jpg',
                    'link'   => route('loja.detalhe') . '/loja'
                ]
            ]
        ];
    }

    /**
     * @return object
     */
    public function automovel(): object
    {
        return $this->index();
    }

    /**
     * @return object
     */
    public function cinema(): object
    {
        return (object)[
            'desktop' => [
                (object) [
                    'imagem' =>'https://clube.marktclub.com.br/images/tela_cinema.jpg',
                    'link' => 'https://clube.marktclub.com.br/images/tela_cinema.jpg'
                ]
            ],
            'mobile' => [
                (object) [
                    'imagem' =>'',
                    'link' => ''
                ]
            ]
        ];
    }
    /**
     * @return object
     */
    public function turismo(): object
    {
        return (object)[
            'desktop' => [
                (object)[
                    'imagem' => LINK_PADRAO . '/images/site/turismo_123_desktop.png',
                    'link'   => ''
                ]
            ],
            'mobile'  => [
                (object)[
                    'imagem' => LINK_PADRAO . '/images/site/turismo_123_mobile.png',
                    'link'   => ''
                ]
            ]
        ];
    }

    /**
     * @return object
     */
    public function turismoCarro(): object
    {
        return (object)[
            'desktop' => [
                (object)[
                    'imagem' => LINK_PADRAO . '/images/site/turismo_carro_desktop.png',
                    'link'   => ''
                ]
            ],
            'mobile'  => [
                (object)[
                    'imagem' => LINK_PADRAO . '/images/site/turismo_carro_mobile.png',
                    'link'   => ''
                ]
            ]
        ];
    }

    /**
     * @return object
     */
    public function sicoob(): object
    {
        return (object)[
            'desktop' => [
                (object)[
                    'imagem' => LINK_PADRAO . '/images/site/credito_sicoob_desktop.jpg',
                    'link'   => ''
                ]
            ],
            'mobile'  => [
                (object)[
                    'imagem' => LINK_PADRAO . '/images/site/credito_sicoob_mobile.jpg',
                    'link'   => ''
                ]
            ]
        ];
    }
    /**
     * @return object
     */
    public function farmacia(): object
    {
        return (object)[
            'desktop' => [
                (object) [
                    'imagem' => LINK_PADRAO . '/images/site/farmacia_desktop.png',
                    'link'   => ''
                ]
            ],
            'mobile' => [
                (object)[
                    'imagem' => LINK_PADRAO . '/images/site/farmacia_desktop.png',
                    'link'   => ''
                ]
            ]
        ];
    }
    /**
     * @return object
     */
    public function saude(): object
    {
        return (object)[
            'desktop' => [
                (object)[
                    'imagem' => LINK_PADRAO . '/images/site/plano_saude_desktop.png',
                    'link'   => ''
                ]
            ],
            'mobile'  => [
                (object)[
                    'imagem' => LINK_PADRAO . '/images/site/plano_saude_desktop.png',
                    'link'   => ''
                ]
            ]
        ];
    }
    /**
     * @return object
     */
    public function alfa(): object
    {
        return (object)[
            'desktop' => [
                (object)[
                    'imagem' => LINK_PADRAO . '/images/site/credito_alfa_desktop.png',
                    'link'   => ''
                ]
            ],
            'mobile'  => [
                (object)[
                    'imagem' => LINK_PADRAO . '/images/site/credito_alfa_mobile.png',
                    'link'   => ''
                ]
            ]
        ];
    }

    /**
     * @return object
     */
    public function corretora(): object
    {
        return (object)[
            'desktop' => [
                (object)[
                    'imagem' => 'https://clube.marktclub.com.br/images/banner_alfa_corretora.png',
                    'link'   => 'https://alfacorretora.com.br/home?utm_source=marketclub&utm_medium=marketclub&utm_campaign=
                    marketclub'
                ]
            ],
            'mobile'  => [
                (object)[
                    'imagem' => 'https://clube.marktclub.com.br/images/banner_alfa_corretora.png',
                    'link'   => 'https://alfacorretora.com.br/home?utm_source=marketclub&utm_medium=marketclub&utm_campaign=
                    marketclub'
                ]
            ]
        ];
    }
    /**
     * @return object
     */
    public function odontologico(): object
    {
        return (object)[
            'desktop' => [
                (object)[
                    'imagem' => 'https://clube.marktclub.com.br/images/banner_topo_odonto_1.png',
                    'link'   => '#'
                ]
            ],
            'mobile'  => [
                (object)[
                    'imagem' => 'https://clube.marktclub.com.br/images/banner_topo_odonto_1.png',
                    'link'   => '#'
                ]
            ]
        ];
    }

    /**
     * @return object
     */
    public function consultoriaAlfa(): object
    {
        return (object)[
            'desktop' => [
                (object)[
                    'imagem' => 'https://clube.marktclub.com.br/images/consultoria_banner_semb2.png',
                    'link'   => route('alfa.consultoriaAlfa')
                ]
            ],
            'mobile'  => [
                (object)[
                    'imagem' => 'https://clube.marktclub.com.br/images/consultoria_banner_semb2.png',
                    'link'   => route('alfa.consultoriaAlfa')
                ]
            ]
        ];
    }

    /**
     * @return object
     */
    public function campanha(): object
    {
        return (object)[
            'desktop' => [
                (object)[
                    'imagem' => 'https://clube.marktclub.com.br/images/consultoria_banner_semb2.png',
                    'link'   => ''
                ]
            ],
            'mobile'  => [
                (object)[
                    'imagem' => 'https://clube.marktclub.com.br/images/consultoria_banner_semb2.png',
                    'link'   => ''
                ]
            ]
        ];
    }
}
