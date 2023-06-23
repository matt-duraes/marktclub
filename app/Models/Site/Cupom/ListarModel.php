<?php

namespace App\Models\Site\Cupom;

use App\Models\Site\ListarInterface;
use Helpers\ApiHelper;
use stdClass;

final class ListarModel extends ApiHelper implements ListarInterface
{
    use MontarRetornoTrait;

    public function __construct()
    {
        parent::__construct(scope: '');
    }

    public function listarDados(string $pesquisa = null): stdClass
    {
        $apiHelper = new ApiHelper('cupom:listar');
        $dado = $apiHelper->parametro([
            'pesquisa' => $this->pesquisa ?? '',
        ])->get('/cupom')->object();

        return $this->montarRetorno($dado);
    }

}
