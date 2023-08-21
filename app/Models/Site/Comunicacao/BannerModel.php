<?php

namespace App\Models\Site\Comunicacao;

use Modules\Botao;
use App\Helpers\ClubeApiHelper;
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
                'tipo'       => Tipo::HOME,
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
}
