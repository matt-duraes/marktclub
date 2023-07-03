<?php

namespace App\Models\Site\Turismo;

use Erro\Excecao;
use Helpers\ApiHelper;
use Http\Request;

final class CidadeHotelModel
{
    protected string $pesquisa;

    public function __construct(
        private readonly Request $request
    ) {
        $this->pesquisa = $this->request->pesquisa;
    }

    /**
     * @return array
     * @throws Excecao
     */
    public function getDado(): array
    {
        $Api = new ApiHelper('turismo:hotel');
        $dado = $Api->json([
            'pesquisa' => $this->pesquisa ?? '',
        ])->get('/turismo/listar-hotel')->array();

        return $this->montarRetorno($dado);
    }

    /**
     * @param $dado
     *
     * @return array
     */
    private function montarRetorno($dado): array
    {
        $retorno = [];
        if (is_array($dado['dado']) && !empty($dado['dado'])) {
            foreach ($dado['dado'] as $key => $valor) {
                $retorno[$key] = $valor;
            }
        }

        return $retorno;
    }
}
