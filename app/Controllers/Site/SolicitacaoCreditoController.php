<?php

namespace App\Controllers\Site;

use Controller\Controller;
use App\Models\Site\Sicoob\SimulacaoModel;
use App\Models\Site\Sicoob\ContratacaoModel;
use Http\Request;
use Http\Response;

final class SolicitacaoCreditoController extends Controller
{
    public function getSimulacao(Request $request): Response
    {
        $operadora = $request->operadora;

        if($operadora == 'sicoob-judiciario') {
            return $this->simularSicoob($request);
        }
    }

    /**
     *
     * @return Response
     * @throws Excecao
     */
    public function simularSicoob(Request $request): Response
    {
        return (new SimulacaoModel($request))->getSimulacao();
    }

    public function postSalvar(Request $request): Response
    {
        $operadora = $request->operadora;
        if($operadora == 'sicoob-judiciario') {
            return $this->salvarSicoob($request);
        }
    }

    /**
     *
     * @return Response
     * @throws Excecao
     */
    public function salvarSicoob(Request $request): Response
    {
        return (new ContratacaoModel($request))->postSalvar();
    }
}
