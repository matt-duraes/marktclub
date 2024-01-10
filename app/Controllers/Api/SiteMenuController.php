<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Api\SiteMenu\MenuModel;
use App\Models\Api\SiteMenu\MenuEntity;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerAtualizarInterface;

final class SiteMenuController extends Controller implements
    ControllerListarInterface,
    ControllerBuscarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    public function getListar(Request $request): Response
    {
        $Menu = new MenuModel();
        return mensagemSucesso([]);
    }

    public function getBuscar(string $id): Response
    {
        $Menu = new MenuEntity();
        $Menu->uuid($id);

        return $this->retornoPadrao($Menu);
    }

    public function postSalvar(Request $request): Response
    {
        $Menu = new MenuEntity();
        $Menu->set(lista: $request->dado());
        $Menu->salvar();

        return $this->retornoPadrao($Menu, 201);
    }

    private function retornoPadrao(MenuEntity $Menu, int $status = 200)
    {
        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity(
                Entity: $Menu,
                lista: []
            ),
            status: $status
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $Menu = new MenuEntity();
        $Menu->uuid($id);
        $Menu->set(lista: $request->dado());
        $Menu->salvar();

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id): Response
    {
        $Menu = new MenuEntity();
        $Menu->uuid($id);
        $Menu->destruir();

        return new Response(status: 204);
    }
}
