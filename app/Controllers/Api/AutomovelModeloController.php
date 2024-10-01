<?php

namespace App\Controllers\Api;

use App\Classes\Automovel\Modelo\Ordem;
use App\Classes\Geral\Status;
use App\Models\Api\Automovel\Modelo\ModeloEntity;
use App\Models\Api\Automovel\Modelo\ModeloModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use Modules\Botao;
use Modules\Data;
use Modules\Pagina;
use Modules\Quantidade;
use System\Interface\ControllerAtualizarInterface;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;

class AutomovelModeloController extends Controller implements
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
        $ModeloEntity = new ModeloEntity();
        $ModeloEntity->idSlug($id);
        return $this->retornoSucesso($ModeloEntity);
    }

    /**
     * @param ModeloEntity $modeloEntity
     * @param int          $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoSucesso(ModeloEntity $modeloEntity, int $status = 200): Response
    {
        return mensagemSucesso(pegarPropriedadeDaEntity($modeloEntity, lista: [
            'parceiro', 'titulo', 'procedimento', 'texto_procedimento',
            'imagem', 'versao', 'data_inicio', 'data_final', 'url',
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
        $ModeloModel = new ModeloModel(
            new Pagina($request->pagina),
            new Quantidade($request->quantidade),
            new Ordem($request->ordem),
            $request->parceiro,
            $request->modelo,
            $request->pesquisa,
            $request->titulo,
            new Botao($request->publicado),
            new Data($request->data_inicio),
            new Data($request->data_final),
            new Status($request->status)
        );
        return mensagemSucesso($ModeloModel->listarDados());
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $ModeloEntity = new ModeloEntity();
        $ModeloEntity->set(lista: $request->dado());
        $ModeloEntity->salvar();
        return $this->retornoSucesso($ModeloEntity, 201);
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
        $ModeloEntity = new ModeloEntity();
        $ModeloEntity->uuid($id);
        $ModeloEntity->set(lista: $request->dado());
        $ModeloEntity->salvar();
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
        $ModeloEntity = new ModeloEntity();
        $ModeloEntity->uuid($id);
        $ModeloEntity->destruir();
        return new Response(status: 204);
    }
}
