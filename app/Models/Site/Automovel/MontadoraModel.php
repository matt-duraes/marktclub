<?php

namespace App\Models\Site\Automovel;

use stdClass;
use App\Helpers\ClubeApiHelper;
use App\Models\Site\ListarInterface;

final class MontadoraModel extends ClubeApiHelper implements ListarInterface
{
    use MontarRetornoTrait;

    public string $tipo = 'montadora';

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
