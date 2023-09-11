<?php

namespace App\Models\Site;

use App\Helpers\ClubeApiHelper;

final class BannerModel extends ClubeApiHelper
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
                    'imagem' => LINK_PADRAO . '/images/temp/index_desktop.png',
                    'target' => '_self',
                    'link'   => route('loja.index')
                ],
                (object)[
                    'imagem' => LINK_PADRAO . '/images/temp/index_desktop.png',
                    'target' => '_self',
                    'link'   => route('loja.index')
                ]
            ],
            'mobile'  => [
                (object)[
                    'imagem' => LINK_PADRAO . '/images/temp/index_mobile.png',
                    'target' => '_self',
                    'link'   => route('loja.index'),
                ],
                (object)[
                    'imagem' => LINK_PADRAO . '/images/temp/index_mobile.png',
                    'target' => '_self',
                    'link'   => route('loja.index')
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
                    'imagem' => LINK_PADRAO . '/images/temp/cinema_desktop.jpeg',
                    'target' => '_self',
                    'link'   => 'https://afiliados.easylive.com.br/?aid=5&category_id=86'
                ]
            ],
            'mobile' => [
                (object) [
                    'imagem' => LINK_PADRAO . '/images/temp/cinema_mobile.jpeg',
                    'target' => '_self',
                    'link'   => 'https://afiliados.easylive.com.br/?aid=5&category_id=86'
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
                    'link'   => '',
                    'target' => ''
                ]
            ],
            'mobile'  => [
                (object)[
                    'imagem' => LINK_PADRAO . '/images/site/turismo_123_mobile.png',
                    'link'   => '',
                    'target' => ''
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
                    'imagem' => LINK_PADRAO . '/images/temp/turismo_carro_desktop.png',
                    'link'   => route('loja.index')
                ]
            ],
            'mobile'  => [
                (object)[
                    'imagem' => LINK_PADRAO . '/images/temp/turismo_carro_mobile.png',
                    'link'   => route('loja.index')
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
                    'imagem' => LINK_PADRAO . '/images/temp/farmacia_desktop.png',
                    'link'   => ''
                ]
            ],
            'mobile' => [
                (object)[
                    'imagem' => LINK_PADRAO . '/images/temp/farmacia_mobile.png',
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
    public function odontologico(): object
    {
        return (object)[
            'desktop' => [
                (object)[
                    'imagem' => LINK . '/images/site/banner_topo_odonto_1.png',
                    'link'   => ''
                ]
            ],
            'mobile'  => [
                (object)[
                    'imagem' => LINK . '/images/site/banner_topo_odonto_1.png',
                    'link'   => ''
                ]
            ]
        ];
    }
}
