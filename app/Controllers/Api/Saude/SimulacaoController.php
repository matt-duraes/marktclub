<?php

namespace App\Controllers\Api\Saude;

use Http\Request;
use Modules\Data;
use Controller\Controller;
use Modules\EnderecoEstado;
use App\Models\Api\Saude\Simulacao\SalvarModel;
use App\Models\Api\Saude\Simulacao\SimularModel;

final class SimulacaoController extends Controller
{
    public function postSimular(Request $request)
    {
        $Simulacao = $this->simular($request);

        return mensagemSucesso($Simulacao->retorno);
    }

    public function postSalvar(Request $request)
    {
        $Salvar = new SalvarModel(
            convenio: $request->convenio,
            simulacao: jsonDecode($request->simulacao, true, true)
        );
        return mensagemSucesso($Salvar->retorno, status: 201);
    }

    private function simular(Request $request)
    {
        return new SimularModel(
            convenio: $request->convenio,
            titular: new Data($request->titular),
            dependente: jsonDecode($request->dependente, true, true),
            estado: new EnderecoEstado($request->estado),
            cidade: $request->cidade
        );
    }
}
