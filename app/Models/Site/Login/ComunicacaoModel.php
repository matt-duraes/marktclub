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

        return (object)[
            'lista'      => $dado->dado->lista,
            'quantidade' => count($dado->dado->lista),
        ];
    }
}
