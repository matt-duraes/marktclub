<?php

namespace App\Controllers\Api;

use App\Classes\Geral\Status;
use App\Models\Api\AlbumFoto\FotoEntity;
use App\Models\Api\AlbumFoto\FotoModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use Modules\Data;
use Modules\Pagina;
use Modules\Quantidade;
use System\Interface\ControllerAtualizarInterface;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;

class AlbumFotoController extends Controller implements
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
        $FotoEntity = new FotoEntity();
        $FotoEntity->uuid($id);
        return $this->retornoPadrao($FotoEntity);
    }

    /**
     * @param FotoEntity $fotoEntity
     * @param int        $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoPadrao(FotoEntity $fotoEntity, int $status = 200): Response
    {
        return mensagemSucesso(pegarPropriedadeDaEntity($fotoEntity, lista: [
            'titulo', 'imagem', 'ordem', 'status'
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
        $FotoModel = new FotoModel(
            new Pagina($request->pagina),
            new Quantidade($request->quantidade),
            $request->album,
            $request->titulo,
            new Data($request->data_inicio),
            new Data($request->data_final),
            new Status($request->status)
        );
        return mensagemSucesso($FotoModel->listarDados());
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $FotoEntity = new FotoEntity();
        $FotoEntity->set(lista: $request->dado());
        $FotoEntity->salvar();
        return $this->retornoPadrao($FotoEntity, 201);
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
        $FotoEntity = new FotoEntity();
        $FotoEntity->uuid($id);
        $FotoEntity->set(lista: $request->dado());
        $FotoEntity->salvar();
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
        $FotoEntity = new FotoEntity();
        $FotoEntity->uuid($id);
        $FotoEntity->destruir();
        return new Response(status: 204);
    }
}
