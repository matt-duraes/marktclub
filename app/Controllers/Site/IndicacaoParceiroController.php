<?php

namespace App\Controllers\Site;

use Controller\Controller;
use Erro\Excecao;
use Http\Response;
use Http\Request;
use App\Models\Site\ConstrutorModel;
use App\Models\Site\IndicacaoParceiro\SalvarModel as SalvarIndicacaoParceiroModel;

final class IndicacaoParceiroController extends Controller
{
    /**
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $indicacao = new SalvarIndicacaoParceiroModel($request);
        $indicacao = $indicacao->postSalvar();

        return new Response(json: [
            'status' => 'sucesso'
        ], status: 201);
    }

}
