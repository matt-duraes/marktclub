<?php

namespace App\Models\Site\Comunicacao;

use App\Classes\ComunicacaoPublicidade\Tipo;
use App\Helpers\ClubeApiHelper;
use Erro\Excecao;
use Modules\Botao;

final class BannerModel extends ClubeApiHelper
{
    use LinkTrait;

    /**
     * @return object
     * @throws Excecao
     */
    public function home(): object
    {
        return $this->buscarBanner(Tipo::HOME);
    }

    /**
     * @param string $tipo
     *
     * @return object
     * @throws Excecao
     */
    private function buscarBanner(string $tipo): object
    {
        $banners = $this
            ->json([
                'pagina'     => 1,
                'quantidade' => 50,
                'tipo'       => $tipo,
                'publicado'  => Botao::SIM
            ])
            ->get('/comunicacao-publicidade')
            ->object()->dado->lista ?? [];
        return $this->montarRetorno($banners);
    }

    /**
     * @param array $banners
     *
     * @return object
     */
    private function montarRetorno(array $banners): object
    {
        if (!$banners) {
            return (object)[
                'desktop' => [],
                'mobile'  => []
            ];
        }
        $desktop = [];
        $mobile = [];
        foreach ($banners as $r) {
            $link = $this->pegarLink($r->link, $r->parceiro->url, $r->parceiro->tipo);
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

    /**
     * @return object
     */
    public function turismo(): object
    {
        return $this->buscarBanner(Tipo::TURISMO);
    }

    /**
     * @return object
     */
    public function lg(): object
    {
        return $this->buscarBanner(Tipo::LG);
    }
}
