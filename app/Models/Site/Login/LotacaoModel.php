<?php

namespace App\Models\Site\Login;

use Helpers\ApiHelper;

final class LotacaoModel
{
    public function buscarLotacao(): array
    {
        $lotacao = (new ApiHelper('site_lotacao:select'))
                    ->get('/site-lotacao/select')
                    ->array();
        return $lotacao;
    }
}
