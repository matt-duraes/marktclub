<?php

namespace App\Models\Site\Samsung;

use Modules\Botao;
use App\Helpers\ClubeApiHelper;
use App\Models\Site\Comunicacao\LinkTrait;
use App\Classes\ComunicacaoPublicidade\Tipo;

final class BannerModel extends ClubeApiHelper
{
    use LinkTrait;

    public function home()
    {
        $dado = $this
            ->json([
                'pagina'     => 1,
                'quantidade' => 50,
                'tipo'       => Tipo::SAMSUNG,
                'publicado'  => Botao::SIM
            ])
            ->get('/comunicacao-publicidade')
            ->object()->dado->lista ?? [];
        return $this->montarRetorno($dado);
    }

    public function samsungCartao()
    {
        $dado = $this
            ->json([
                'pagina'     => 1,
                'quantidade' => 50,
                'tipo'       => Tipo::CARTAOSAMSUNG,
                'publicado'  => Botao::SIM
            ])
            ->get('/comunicacao-publicidade')
            ->object()->dado->lista ?? [];
        return $this->montarRetorno($dado);
    }

    private function montarRetorno($dado)
    {
        if (!$dado) {
            return (object)[
                'desktop' => [],
                'mobile'  => []
            ];
        }
        $desktop = [];
        $mobile = [];
        foreach ($dado as $r) {
            if ((new Tipo($r->tipo))->indice() === Tipo::SAMSUNG && empty($r->link)) {
                $link = '/samsung';
            } else {
                $link = $this->pegarLink($r->link, $r->parceiro->url, $r->parceiro->tipo);
            }
            $target = $this->pegarTarget($link);
            if (!empty($r->imagem_desktop)) {
                $desktop[] = (object)[
                    'id'     => $r->id,
                    'link'   => $link,
                    'target' => $target,
                    'imagem' => $r->imagem_desktop
                ];
            }
            if (!empty($r->imagem_mobile)) {
                $mobile[] = (object)[
                    'id'     => $r->id,
                    'link'   => $link,
                    'target' => $target,
                    'imagem' => $r->imagem_mobile
                ];
            }
        }
        return (object)[
            'desktop' => $desktop,
            'mobile'  => $mobile
        ];
    }

    public function samsungFixo(): object
    {

        return (object)[
            'desktop' => [
                (object) [
                    'imagem' => LINK . '/images/site/banner_samsung_fixo_desktop.png',
                    'target' => '',
                    'link'   => 'https://samsung.com.br/services/cartao-samsung/'                ]
            ],
            'mobile' => [
                (object) [
                    'imagem' => LINK . '/images/site/banner_samsung_fixo_mobile.png',
                    'target' => '',
                    'link'   => 'https://samsung.com.br/services/cartao-samsung/'                ]
            ]
        ];
    }
}
