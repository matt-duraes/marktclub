<?php

namespace App\Controllers\Site;

use Http\Request;
use Http\Response;
use Modules\Dinheiro;
use Controller\Controller;
use App\Helpers\ClubeApiHelper;
use App\Models\Site\Sicoob\ContratacaoModel;

final class SolicitacaoCreditoController extends Controller
{
    public function postRealizarSimulacao(Request $request): Response
    {
        $dado = (new ClubeApiHelper())
            ->validar(mensagem: 'Erro ao fazer sua simulação, por favor, tente novamente.', login: true)
            ->json([
                'operadora'       => $request->operadora,
                'tipo'            => $request->tipo,
                'valor_total'     => (new Dinheiro($request->valor_total))->decimal(),
                'parcela'         => $request->parcela,
            ])
            ->get('/solicitacao-credito/simulacao')
            ->object();

        return mensagemSucesso([
            'valor_total'   => strDinheiro($dado->dado->valor_total),
            'valor_parcela' => strDinheiro($dado->dado->valor_parcela),
            'parcela'       => $dado->dado->parcela
        ]);
    }

    public function postSalvar(Request $request): Response
    {
        $dado = (new ClubeApiHelper())
            ->validar(mensagem: 'Erro ao salvar sua solicitação, por favor, tente novamente.', login: true)
            ->body([
                'operadora'        => $request->operadora,
                'tipo'             => $request->tipo,
                'valor'            => number_format((float) $request->valor, 2, '.', ''),
                'parcelas'         => $request->parcelas,
            ])
            ->post('/solicitacao-credito')
            ->object();

        return mensagemSucesso($dado, status: 201);
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
