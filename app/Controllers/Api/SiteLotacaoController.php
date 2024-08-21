<?php

namespace App\Controllers\Api;

use Erro\Excecao;
use Http\Request;
use Http\Response;
use Modules\Pagina;
use Modules\Quantidade;
use Controller\Controller;
use App\Classes\Geral\Status;
use App\Models\Api\SiteLotacao\SelectModel;
use App\Models\Api\SiteLotacao\LotacaoModel;
use App\Models\Api\SiteLotacao\LotacaoEntity;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerSelectInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerAtualizarInterface;

final class SiteLotacaoController extends Controller implements
    ControllerSelectInterface,
    ControllerBuscarInterface,
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getSelect(Request $request): Response
    {
        $SelectModel = new SelectModel($request);
        return mensagemSucesso($SelectModel->listarDados());
    }

    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function getBuscar(string $id): Response
    {
        $LotacaoEntity = new LotacaoEntity();
        $LotacaoEntity->uuid($id);
        return $this->retornoPadrao($LotacaoEntity);
    }

    /**
     * @param LotacaoEntity $lotacaoEntity
     * @param int         $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoPadrao(LotacaoEntity $lotacaoEntity, int $status = 200): Response
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity($lotacaoEntity, lista: [
                'slug', 'titulo', 'status', 'data_criacao', 'data_atualizacao'
            ]),
            $status
        );
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getListar(Request $request): Response
    {
        $LotacaoModel = new LotacaoModel(
            new Pagina($request->pagina),
            new Quantidade($request->quantidade),
            $request->empresa,
            new Status($request->status)
        );
        return mensagemSucesso($LotacaoModel->listarDados());
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $LotacaoEntity = new LotacaoEntity();
        $LotacaoEntity->set(lista: $request->dado());
        $LotacaoEntity->salvar();
        return $this->retornoPadrao($LotacaoEntity, 201);
    }

    /**
     * @param Request $request
     * @param string  $id
     *
     * @return Response
     * @throws Excecao
     */
    public function putAtualizar(Request $request, string $id): Response
    {
        $LotacaoEntity = new LotacaoEntity();
        $LotacaoEntity->uuid($id);
        $LotacaoEntity->set(lista: $request->dado());
        $LotacaoEntity->salvar();
        return new Response(status: 204);
    }

    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function deleteDeletar(string $id): Response
    {
        $LotacaoEntity = new LotacaoEntity();
        $LotacaoEntity->uuid($id);
        $LotacaoEntity->destruir();
        return new Response(status: 204);
    }
}
