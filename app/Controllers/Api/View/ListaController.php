<?php

namespace App\Controllers\Api\View;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Api\View\Lista\ListaModel;
use App\Models\Api\View\Lista\ListaEntity;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerAtualizarInterface;

final class ListaController extends Controller implements
    ControllerListarInterface,
    ControllerBuscarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    public function getListar(Request $request): Response
    {
        $Lista = new ListaModel(request: $request);
        return mensagemSucesso($Lista->retorno);
    }

    public function getBuscar(string $id): Response
    {
        $Lista = new ListaEntity();
        $Lista->uuid($id);

        return $this->retornoPadrao(Lista: $Lista, status: 200);
    }

    public function postSalvar(Request $request): Response
    {
        $Lista = new ListaEntity();
        $Lista->set(lista: $request->dado());
        $Lista->salvar();

        return $this->retornoPadrao(Lista: $Lista, status: 201);
    }

    private function retornoPadrao(ListaEntity $Lista, int $status)
    {
        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity(
                Entity: $Lista,
                lista: [
                    'pagina', 'tipo', 'local', 'titulo', 'texto', 'link', 'target', 'arquivo',
                    'api_status', 'api_scope', 'api_uri', 'api_metodo', 'api_body', 'status'
                ]
            ),
            status: $status
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $Lista = new ListaEntity();
        $Lista->uuid($id);
        $Lista->set(lista: $request->dado());
        $Lista->salvar();
        return new Response(status: 204);
    }

    public function deleteDeletar(string $id): Response
    {
        $Lista = new ListaEntity();
        $Lista->uuid($id);
        $Lista->destruir();

        return new Response(status: 204);
    }
}
