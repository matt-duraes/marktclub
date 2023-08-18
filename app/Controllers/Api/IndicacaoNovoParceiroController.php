<?php

namespace App\Controllers\Api;

use App\Models\Api\IndicacaoNovoParceiro\IndicacaoNovoParceiroEntity;
use App\Models\Api\IndicacaoNovoParceiro\IndicacaoNovoParceiroModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;

class IndicacaoNovoParceiroController extends Controller implements
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerDeletarInterface
{
    /**
     * @throws Excecao
     */
    public function getListar(Request $request): Response
    {
        $IndicacaoNovoParceiro = new IndicacaoNovoParceiroModel($request);
        return mensagemSucesso($IndicacaoNovoParceiro->listarDados());
    }

    public function getBuscar(string $id): Response
    {
        $IndicacaoNovoParceiro = new IndicacaoNovoParceiroEntity();
        $IndicacaoNovoParceiro->uuid($id);

        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $IndicacaoNovoParceiro,
                lista: ['nome_indicado', 'email_indicado', 'telefone_indicado', 'mensagem', 'status']
            )
        );
    }

    /**
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $IndicacaoNovoParceiro = new IndicacaoNovoParceiroEntity();

        $IndicacaoNovoParceiro->set(lista: $request->dado());
        $IndicacaoNovoParceiro->salvar();

        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $IndicacaoNovoParceiro,
                lista: ['nome_indicado', 'email_indicado', 'telefone_indicado', 'mensagem', 'status']
            ),
            201
        );
    }

    /**
     * @throws Excecao
     */
    public function putAtualizarStatus(string $id, Request $request): Response
    {
        $IndicacaoNovoParceiro = new IndicacaoNovoParceiroEntity();

        $IndicacaoNovoParceiro->uuid($id);
        $IndicacaoNovoParceiro->set('status', $request->dado('status')['status'], );
        $IndicacaoNovoParceiro->salvar();

        return new Response(status: 204);
    }

    /**
     * @throws Excecao
     */
    public function deleteDeletar(string $id): Response
    {
        $IndicacaoNovoParceiro = new IndicacaoNovoParceiroEntity();

        $IndicacaoNovoParceiro->uuid($id);
        $IndicacaoNovoParceiro->destruir();

        return new Response(status: 204);
    }
}
