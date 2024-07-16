<?php

namespace App\Models\Site\Login;

use Helpers\ApiHelper;

final class ComunicacaoModel
{
    public function buscarBanner()
    {
        $dado = (new ApiHelper('comunicacao_login:buscar'))
            ->json([
                'empresa' => sessao('CLUBE')->empresa,
            ])
            ->get('/comunicacao-login/clube')
            ->object();
        if (!chaveExiste('dado', $dado)) {
            return (object)[
                'lista'      => [],
                'quantidade' => 0,
            ];
        }

        $banner = [];
        if (!empty($dado->dado->arquivo_1)) {
            $banner[] = arquivoPrivado($dado->dado->arquivo_1);
        }
        if (!empty($dado->dado->arquivo_2)) {
            $banner[] = arquivoPrivado($dado->dado->arquivo_2);
        }
        if (!empty($dado->dado->arquivo_3)) {
            $banner[] = arquivoPrivado($dado->dado->arquivo_3);
        }

        return (object)[
            'lista'      => $banner,
            'quantidade' => count($banner),
        ];
    }
}
