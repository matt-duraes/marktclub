<?php

namespace App\Controllers\Api\Parceiro;

use Http\Request;
use Http\Response;
use Controller\Controller;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use App\Models\Api\Parceiro\Turismo\TurismoModel;
use App\Models\Api\Parceiro\Turismo\TurismoEntity;
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
        $Turismo = new TurismoModel();
        $Turismo->set(lista: $request->dado());

        return mensagemSucesso($Turismo->listarDados());
    }

    public function getBuscar(string $id): Response
    {
        $Turismo = new TurismoEntity();
        $Turismo->uuid($id);

        return $this->retornoPadrao($Turismo, 200);
    }

    public function postSalvar(Request $request): Response
    {
        $Turismo = new TurismoEntity();
        $Turismo->set(lista: $request->dado());
        $Turismo->salvar();

        return $this->retornoPadrao($Turismo, 201);
    }

    private function retornoPadrao(TurismoEntity $Turismo, int $status)
    {
        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity(
                Entity: $Turismo,
                lista: [
                    'titulo', 'texto', 'data_inicio', 'data_final', 'imagem', 'status'
                ]
            ),
            status: $status
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $Turismo = new TurismoEntity();
        $Turismo->uuid($id);
        $Turismo->set(lista: $request->dado());
        $Turismo->salvar();

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id): Response
    {
        $Turismo = new TurismoEntity();
        $Turismo->uuid($id);
        $Turismo->destruir();

        return new Response(status: 204);
    }
}
