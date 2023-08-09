<?php

namespace App\Controllers\Site;

use App\Helpers\ClubeApiHelper;
use Controller\Controller;
use App\Models\Site\Sicoob\ContratacaoModel;
use Http\Request;
use Http\Response;

final class SolicitacaoCreditoController extends Controller
{
    public function getRealizarSimulacao(Request $request): Response
    {
        $dado = ((new ClubeApiHelper()))
        ->parametro([
            'operadora'        => $request->operadora,
            'tipo'             => $request->tipo,
            'valor'            => number_format((float) $request->valor, 2, '.', ''),
            'parcelas'         => $request->parcelas,
        ])
        ->get('/solicitar-credito')
        ->object();
        return new Response(json:$dado);
    }

    public function postSalvar(Request $request): Response
    {
        $dado = ((new ClubeApiHelper()))
        ->body([
            'operadora'        => $request->operadora,
            'tipo'             => $request->tipo,
            'valor'            => number_format((float) $request->valor, 2, '.', ''),
            'parcelas'         => $request->parcelas,
        ])
        ->post('/solicitacao-credito')
        ->object();
        return new Response(json:$dado);
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
