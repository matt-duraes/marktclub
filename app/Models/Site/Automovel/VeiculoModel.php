<?php

namespace App\Models\Site\Automovel;

use App\Models\Site\ListarInterface;
use Helpers\ApiHelper;
use stdClass;

final class VeiculoModel extends ApiHelper implements ListarInterface
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
                    'pagina' => 1,
                    'montadora' => $this->url
                ])->get('/automovel-modelo')->object();

        return $this->montarRetorno($dado);
    }
}
