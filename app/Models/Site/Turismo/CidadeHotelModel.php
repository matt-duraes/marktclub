<?php

namespace App\Models\Site\Turismo;

use stdClass;
use Http\Request;
use Http\Response;
use Helpers\ApiHelper;
use Helpers\ListaHelper;

final class CidadeHotelModel
{
    protected string $pesquisa;

    public function __construct(
        private Request $request
    ) {

        $this->pesquisa = $request->pesquisa;

    }

    public function getDado()
    {
        $Api = new ApiHelper('turismo:hotel');
        $dado = $Api->json([
            'pesquisa' => $this->pesquisa ?? '',
        ])->get('/turismo/listar-hotel')->array();

        return $this->montarRetorno($dado);
    }

    private function montarRetorno($dado)
    {

        $retorno = [];
        if(is_array($dado['dado']) && !empty($dado['dado'])):
            foreach($dado['dado'] as $key => $valor):
                $retorno[$key] = $valor;
            endforeach;
        endif;

        return $retorno;
    }



}
