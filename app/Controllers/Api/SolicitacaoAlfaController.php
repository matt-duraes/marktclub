<?php

namespace App\Controllers\Api;

use App\Models\Api\SolicitacaoAlfa\SolicitacaoEntity;
use Erro\Excecao;
use Http\Request;
use Http\Response;

class SolicitacaoAlfaController
{
    /**
     * @param  string  $id
     *
     * @return Response
     * @throws Excecao
     */
    public function getTermos(string $id): Response
    {
        $solicitacaoCreditoEntity = new SolicitacaoEntity();
        $solicitacaoCreditoEntity->uuid($id);

        return mensagemSucesso([
            'termos' => $solicitacaoCreditoEntity->get('termos')
        ]);
    }

    /**
     * @param  string  $id
     *
     * @return Response
     * @throws Excecao
     */
    public function getTaxas(string $id): Response
    {
        $solicitacaoCreditoEntity = new SolicitacaoEntity();
        $solicitacaoCreditoEntity->uuid($id);

        return mensagemSucesso([
            'taxas' => $solicitacaoCreditoEntity->get('taxas')
        ]);
    }

    /**
     * @param  Request  $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSolicitacao(Request $request): Response
    {
        $solicitacaoCreditoEntity = new SolicitacaoEntity();
        $solicitacaoCreditoEntity->set(lista: $request->dado());
        $solicitacaoCreditoEntity->salvar();

        return new Response(status: 201);
    }
}
