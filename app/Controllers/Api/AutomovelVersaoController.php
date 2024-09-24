<?php

namespace App\Controllers\Api;

use App\Classes\Automovel\Versao\Ordem;
use App\Classes\Geral\Status;
use App\Models\Api\Automovel\Versao\VersaoEntity;
use App\Models\Api\Automovel\Versao\VersaoModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use Modules\Pagina;
use Modules\Quantidade;
use System\Interface\ControllerAtualizarInterface;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;

class AutomovelVersaoController extends Controller implements
    ControllerBuscarInterface,
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function getBuscar(string $id): Response
    {
        $VersaoEntity = new VersaoEntity();
        $VersaoEntity->uuid($id);
        return $this->retornoSucesso($VersaoEntity);
    }

    /**
     * @param VersaoEntity $versaoEntity
     * @param int          $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoSucesso(VersaoEntity $versaoEntity, int $status = 200): Response
    {
        return mensagemSucesso(pegarPropriedadeDaEntity($versaoEntity, lista: [
            'titulo', 'imagem', 'imagemUrl', 'cor', 'valor_de', 'valor_por',
            'status', 'data_criacao', 'data_atualizacao'
        ]), $status);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getListar(Request $request): Response
    {
        $VersaoModel = new VersaoModel(
            new Pagina($request->pagina),
            new Quantidade($request->quantidade),
            new Ordem($request->ordem),
            $request->parceiro,
            $request->modelo,
            new Status($request->status)
        );
        return mensagemSucesso($VersaoModel->listarDados());
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $VersaoEntity = new VersaoEntity();
        $VersaoEntity->set(lista: $request->dado());
        $VersaoEntity->salvar();
        return $this->retornoSucesso($VersaoEntity, 201);
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
        $VersaoEntity = new VersaoEntity();
        $VersaoEntity->uuid($id);
        $VersaoEntity->set(lista: $request->dado());
        $VersaoEntity->salvar();
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
        $VersaoEntity = new VersaoEntity();
        $VersaoEntity->uuid($id);
        $VersaoEntity->destruir();
        return new Response(status: 204);
    }
}
