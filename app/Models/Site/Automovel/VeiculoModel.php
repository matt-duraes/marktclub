<?php

namespace App\Models\Site\Automovel;

use stdClass;
use App\Helpers\ClubeApiHelper;
use App\Models\Site\ListarInterface;

final class VeiculoModel extends ClubeApiHelper implements ListarInterface
{
    use MontarRetornoTrait;

    public string $tipo = 'modelo';

    public function __construct(
        protected ?string $url = null
    ) {
        parent::__construct(scope: '');
    }

    public function listarDados(): stdClass
    {
        $apiHelper = new ApiHelper('automovel_modelo:listar');

        $dado = $apiHelper->json([
            'pagina'    => 1,
            'montadora' => $this->url
        ])->get('/automovel-modelo')->object();

        return $this->montarRetorno($dado);
    }
}
