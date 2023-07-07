<?php

namespace App\Models\Site\Cupom;

use stdClass;
use App\Helpers\ClubeApiHelper;
use App\Models\Site\ListarInterface;

final class ListarModel extends ClubeApiHelper implements ListarInterface
{
    use MontarRetornoTrait;

    public function listarDados(string $pesquisa = null): stdClass
    {
        $dado = $this->json([
            'pesquisa' => $pesquisa ?? '',
        ])->get('/parceiro-cupom')->object();

        return $this->montarRetorno($dado);
    }
}
