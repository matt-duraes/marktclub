<?php

namespace App\Controllers\Api\Demanda;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Api\Demanda\Sprint\SprintModel;
use App\Models\Api\Demanda\Sprint\SprintEntity;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerAtualizarInterface;

class SprintController extends Controller implements
    ControllerBuscarInterface,
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface
{
    public function getListar(Request $request): Response
    {
        $Sprint = new SprintModel();
        $Sprint->set(lista: $request->dado());
        return mensagemSucesso($Sprint->listarDados());
    }

    public function getBuscar(string $id): Response
    {
        $Sprint = new SprintEntity();
        $Sprint->uuid($id);
        return $this->retornoSucesso($Sprint);
    }

    public function postSalvar(Request $request): Response
    {
        $dado = $request->dado();
        if ($request->existe('texto_inicio')) {
            $dado['texto_inicio'] = $request->getPost('texto_inicio');
        }

        $Sprint = new SprintEntity();
        $Sprint->set(lista: $dado);
        $Sprint->salvar();
        return $this->retornoSucesso($Sprint, 201);
    }

    private function retornoSucesso(SprintEntity $Sprint, int $status = 200): Response
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity($Sprint, lista: [
                'titulo', 'texto_inicio', 'texto_final', 'data_inicio', 'data_final', 'status'
            ]),
            $status
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $dado = $request->dado();
        if ($request->existe('texto_final')) {
            $dado['texto_final'] = $request->getPut('texto_final');
        }

        $Sprint = new SprintEntity();
        $Sprint->uuid($id);
        $Sprint->set(lista: $dado);
        $Sprint->salvar();

        return new Response(status: 204);
    }
}
