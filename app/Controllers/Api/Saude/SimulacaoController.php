<?php

namespace App\Controllers\Api\Saude;

use Http\Request;
use Modules\Data;
use Controller\Controller;
use App\Models\Api\Saude\Simulacao\SalvarModel;
use App\Models\Api\Saude\Simulacao\SimularModel;

final class SimulacaoController extends Controller
{
    public function getSimular(Request $request)
    {
        $Simulacao = $this->simular($request);

        return mensagemSucesso($Simulacao->retorno);
    }

    public function postSalvar(Request $request)
    {
        $Salvar = new SalvarModel(
            Simulacao: $this->simular($request),
            convenio: $request->convenio,
            escolhido: $request->escolhido
        );
        return mensagemSucesso($Salvar->retorno, status: 201);
    }

    private function simular(Request $request)
    {
        return new SimularModel(
            escolhido: $request->escolhido,
            titular: new Data($request->titular),
            dependente: $request->dependente,
            convenio: $request->convenio
        );
    }
}
