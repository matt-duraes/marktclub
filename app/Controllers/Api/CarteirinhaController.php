<?php

namespace App\Controllers\Api;

use Erro\Excecao;
use Http\Request;
use Http\Response;
use Modules\Pagina;
use Modules\Quantidade;
use Controller\Controller;
use App\Classes\Carteirinha\Ordem;
use App\Classes\Carteirinha\Status;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use App\Models\Api\Carteirinha\CarteirinhaModel;
use System\Interface\ControllerDeletarInterface;
use App\Models\Api\Carteirinha\CarteirinhaEntity;
use System\Interface\ControllerAtualizarInterface;

class CarteirinhaController extends Controller implements
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
        $CarteirinhaEntity = new CarteirinhaEntity();
        $CarteirinhaEntity->uuid($id);
        return $this->retornoSucesso($CarteirinhaEntity);
    }

    /**
     * @param CarteirinhaEntity $carteirinhaEntity
     * @param int               $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoSucesso(CarteirinhaEntity $carteirinhaEntity, int $status = 200): Response
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity($carteirinhaEntity, lista: [
                'empresa', 'bg_frente', 'bg_fundo', 'titulo', 'estado', 'nome', 'cpf', 'data_nascimento', 'matricula',
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
        $CarteirinhaModel = new CarteirinhaModel(
            new Pagina($request->pagina),
            new Quantidade($request->quantidade),
            new Ordem($request->ordem),
            $request->empresa,
            new Status($request->status)
        );
        return mensagemSucesso($CarteirinhaModel->listarDados());
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $CarteirinhaEntity = new CarteirinhaEntity();
        $CarteirinhaEntity->set(lista: $request->dado());
        $CarteirinhaEntity->salvar();
        return $this->retornoSucesso($CarteirinhaEntity, 201);
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
        $CarteirinhaEntity = new CarteirinhaEntity();
        $CarteirinhaEntity->uuid($id, mensagem: 'Modelo de carteirinha não encontrado ou inexistente');
        $CarteirinhaEntity->set(lista: $request->dado());
        $CarteirinhaEntity->salvar();
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
        $CarteirinhaEntity = new CarteirinhaEntity();
        $CarteirinhaEntity->uuid($id, mensagem: 'Modelo de carteirinha não encontrado ou inexistente');
        $CarteirinhaEntity->destruir();
        return new Response(status: 204);
    }
}
