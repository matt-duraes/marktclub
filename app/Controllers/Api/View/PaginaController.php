<?php

namespace App\Controllers\Api\View;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Api\View\Pagina\ViewModel;
use App\Models\Api\View\Pagina\ViewEntity;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerAtualizarInterface;

final class PaginaController extends Controller implements
    ControllerListarInterface,
    ControllerBuscarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    public function getListar(Request $request): Response
    {
        $View = new ViewModel(request: $request);
        return mensagemSucesso($View->retorno);
    }

    public function getBuscar(string $id): Response
    {
        $View = new ViewEntity();
        $View->idSlug($id);
        return $this->retornoPadrao($View, 200);
    }

    public function postSalvar(Request $request): Response
    {
        $View = new ViewEntity();
        $View->set(lista: $request->dado());
        $View->salvar();

        return $this->retornoPadrao($View, 201);
    }

    private function retornoPadrao(ViewEntity $View, int $status)
    {
        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity(
                Entity: $View,
                lista: ['id', 'titulo', 'url', 'html']
            ),
            status: $status
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $View = new ViewEntity();
        $View->uuid($id);
        $View->html = jsonDecode($request->getPut('html', false, false), true);
        $View->salvar();
        return new Response(status: 204);
    }

    public function deleteDeletar(string $id): Response
    {
        $View = new ViewEntity();
        $View->uuid($id);
        $View->destruir();
        return new Response(status: 204);
    }
}
