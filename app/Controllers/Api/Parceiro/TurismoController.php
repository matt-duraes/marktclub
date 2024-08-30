<?php

namespace App\Controllers\Api\Parceiro;

use Http\Request;
use Http\Response;
use Controller\Controller;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerAtualizarInterface;

final class TurismoController extends Controller implements
    ControllerBuscarInterface,
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    public function getListar(Request $request): Response
    {
        return mensagemSucesso([]);
    }

    public function getBuscar(string $id): Response
    {
        return $this->retornoPadrao();
    }

    public function postSalvar(Request $request): Response
    {
        return $this->retornoPadrao();
    }

    private function retornoPadrao()
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
