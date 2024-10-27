<?php

namespace App\Controllers\Api\View;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Api\View\Html\HtmlModel;
use App\Models\Api\View\Html\HtmlEntity;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerAtualizarInterface;

final class HtmlController extends Controller implements
    ControllerListarInterface,
    ControllerBuscarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    public function getListar(Request $request): Response
    {
        $Html = new HtmlModel();
        return mensagemSucesso([]);
    }

    public function getBuscar(string $id): Response
    {
        $Html = new HtmlEntity();
        $Html->uuid($id);

        return $this->retornoPadrao(Html: $Html, status: 200);
    }

    public function postSalvar(Request $request): Response
    {
        $dado = $request->dado();
        $Html = new HtmlEntity();
        $Html->set(lista: $dado);
        $Html->salvar();

        return $this->retornoPadrao(Html: $Html, status: 201);
    }

    private function retornoPadrao(HtmlEntity $Html, int $status)
    {
        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity($Html),
            status: $status
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $dado = $request->dado();
        $Html = new HtmlEntity();
        $Html->uuid($id);
        $Html->set(lista: $dado);
        $Html->salvar();

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id): Response
    {
        $Html = new HtmlEntity();
        $Html->uuid($id);
        $Html->destruir();

        return new Response(status: 204);
    }
}
