<?php

namespace App\Controllers\Api;

use App\Classes\Geral\Status;
use App\Models\Api\SiteCargo\CargoEntity;
use App\Models\Api\SiteCargo\CargoModel;
use App\Models\Api\SiteCargo\SelectModel;
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
use System\Interface\ControllerSelectInterface;

final class SiteCargoController extends Controller implements
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
        $CargoEntity = new CargoEntity();
        $CargoEntity->uuid($id);
        return $this->retornoPadrao($CargoEntity);
    }

    /**
     * @param CargoEntity $cargoEntity
     * @param int         $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoPadrao(CargoEntity $cargoEntity, int $status = 200): Response
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity($cargoEntity, lista: [
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
        $CargoModel = new CargoModel(
            new Pagina($request->pagina),
            new Quantidade($request->quantidade),
            $request->empresa,
            new Status($request->status)
        );
        return mensagemSucesso($CargoModel->listarDados());
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $CargoEntity = new CargoEntity();
        $CargoEntity->set(lista: $request->dado());
        $CargoEntity->salvar();
        return $this->retornoPadrao($CargoEntity, 201);
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
        $CargoEntity = new CargoEntity();
        $CargoEntity->uuid($id);
        $CargoEntity->set(lista: $request->dado());
        $CargoEntity->salvar();
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
        $CargoEntity = new CargoEntity();
        $CargoEntity->uuid($id);
        $CargoEntity->destruir();
        return new Response(status: 204);
    }
}
