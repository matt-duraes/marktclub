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

final class AutomovelModeloController extends Controller implements
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
        $Modelo = new ModeloEntity();
        $Modelo->idSlug($id);
        return $this->retornoSucesso($Modelo);
    }

    /**
     * @param ModeloEntity $Modelo
     * @param int          $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoSucesso(ModeloEntity $Modelo, int $status = 200): Response
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity($Modelo, lista: [
                'parceiro', 'titulo', 'procedimento', 'texto_procedimento',
                'imagem', 'versao', 'data_inicio', 'data_final', 'url',
                'status', 'data_criacao', 'data_atualizacao'
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
        $Modelo = new ModeloModel(
            new Pagina($request->pagina),
            new Quantidade($request->quantidade),
            new Ordem($request->ordem),
            $request->parceiro,
            $request->pesquisa,
            new Botao($request->publicado),
            new Data($request->data_inicio),
            new Data($request->data_final),
            new Status($request->status)
        );
        return mensagemSucesso($Modelo->listarDados());
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $Modelo = new ModeloEntity();
        $Modelo->set(lista: $request->dado());
        $Modelo->salvar();
        return $this->retornoSucesso($Modelo, 201);
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
        $Modelo = new ModeloEntity();
        $Modelo->uuid($id);
        $Modelo->set(lista: $request->dado());
        $Modelo->salvar();
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
        $Modelo = new ModeloEntity();
        $Modelo->uuid($id);
        $Modelo->destruir();
        return new Response(status: 204);
    }
}
