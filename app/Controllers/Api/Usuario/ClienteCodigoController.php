<?php

namespace App\Controllers\Api\Usuario;

use App\Classes\Geral\Status;
use App\Classes\UsuarioClienteCodigo\Ordem;
use App\Models\Api\UsuarioCodigo\CodigoEntity;
use App\Models\Api\UsuarioCodigo\CodigoModel;
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

class ClienteCodigoController extends Controller implements
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
        $CodigoEntity = new CodigoEntity();
        $CodigoEntity->uuid($id);
        return $this->retornoSucesso($CodigoEntity);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getListar(Request $request): Response
    {
        $CodigoModel = new CodigoModel(
            new Pagina($request->pagina),
            new Quantidade($request->quantidade),
            new Ordem($request->ordem),
            $request->empresa,
            $request->subempresa,
            new Data($request->data_inicio),
            new Data($request->data_final),
            new Status($request->status)
        );
        return mensagemSucesso($CodigoModel->listarDados());
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $CodigoEntity = new CodigoEntity();
        $CodigoEntity->set(lista: $request->dado());
        $CodigoEntity->salvar();
        return $this->retornoSucesso($CodigoEntity, 201);
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
        $CodigoEntity = new CodigoEntity();
        $CodigoEntity->uuid($id);
        $CodigoEntity->set(lista: $request->dado());
        $CodigoEntity->salvar();
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
        $CodigoEntity = new CodigoEntity();
        $CodigoEntity->uuid($id);
        $CodigoEntity->destruir();
        return new Response(status: 204);
    }

    /**
     * @param CodigoEntity $codigoEntity
     * @param int          $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoSucesso(CodigoEntity $codigoEntity, int $status = 200): Response
    {
        return mensagemSucesso(pegarPropriedadeDaEntity($codigoEntity, lista: [
            'empresa', 'subempresa', 'codigo', 'data_criacao',
            'data_atualizacao', 'status'
        ]), $status);
    }
}
