<?php

namespace App\Models\Site\Automovel;

use App\Models\Site\ListarInterface;
use Helpers\ApiHelper;
use stdClass;

final class MontadoraModel extends ApiHelper implements ListarInterface
{
    use MontarRetornoTrait;

    public function __construct()
    {
        parent::__construct(scope: '');
    }

    public function listarDados(): stdClass
    {
        $apiHelper = new ApiHelper('automovel_montadora:listar');

        $dado = $apiHelper->json([
                    'pagina' => 1
                ])->get('/automovel-montadora')->object();

        return $this->montarRetorno($dado);
    }
}
