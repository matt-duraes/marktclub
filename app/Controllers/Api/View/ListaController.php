<?php

namespace App\Controllers\Api\View;

use Http\Request;
use Http\Response;
use Controller\Controller;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerAtualizarInterface;

final class ListaController extends Controller implements
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    public function getListar(Request $request): Response
    {
        return mensagemSucesso([]);
    }

    public function postSalvar(Request $request): Response
    {
        return mensagemSucesso([]);
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        return new Response(status: 204);
    }

    public function deleteDeletar(string $id): Response
    {
        return new Response(status: 204);
    }
}
