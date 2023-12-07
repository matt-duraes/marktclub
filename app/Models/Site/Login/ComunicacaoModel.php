<?php

namespace App\Models\Site\Login;

use Helpers\ApiHelper;

final class ComunicacaoModel
{
    public function buscarBanners()
    {
        $dado = (new ApiHelper('comunicacao_login:listar'))
            ->json([
                'empresa'   => sessao('CLUBE')->empresa,
                'pagina'    => 1,
                'publicado' => 'sim'
            ])
            ->get('/comunicacao-login')
            ->array()['dado'] ?? [];

        $lista = $dado['lista'] ?? [];
        $banner = [];

        foreach ($lista[0]['url'] ?? [] as $b) {
            if (!empty($b)) {
                $banner[] = $b;
            }
        }

        return (object)[
            'lista'               => $banner ?? [],
            'quantidade_banners'  => count($banner),
        ];
    }
}
