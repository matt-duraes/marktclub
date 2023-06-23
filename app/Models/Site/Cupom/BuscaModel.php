<?php

namespace App\Models\Site\Cupom;

use App\Models\Site\ListarInterface;
use Helpers\ApiHelper;
use stdClass;

final class BuscaModel extends ApiHelper
{
    use MontarRetornoTrait;

    public function __construct()
    {
        parent::__construct(scope: '');
    }

    public function listarDados(string $url = null): stdClass
    {
        $apiHelper = new ApiHelper('cupom:buscar');
        $dado = $apiHelper->get('/cupom/'.$url)->object();
        return $this->montarRetorno($dado);
    }

}
