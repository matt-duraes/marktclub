<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Api\ComercialRegra\RegraModel;
use App\Models\Api\ComercialRegra\RegraEntity;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerAtualizarInterface;

final class ComercialRegraController extends Controller implements
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerBuscarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    public function getListar(Request $request): Response
    {
        $Regra = new RegraModel($request);
        return mensagemSucesso($Regra->listarDados());
    }

    public function getBuscar(string $id): Response
    {
        $Regra = new RegraEntity();
        $Regra->uuid($id);

        return $this->retornoPadrao($Regra);
    }

    public function postSalvar(Request $request): Response
    {
        $dado = $request->dado();
        $dado['texto'] = $request->getPost('texto', html: false);

        $Regra = new RegraEntity();
        $Regra->set(lista: $dado);
        $Regra->salvar();

        return $this->retornoPadrao($Regra, 201);
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $dado = $request->dado();
        $dado['texto'] = $request->getPut('texto', html: false);

        $Regra = new RegraEntity();
        $Regra->uuid($id);
        $Regra->set(lista: $dado);
        $Regra->salvar();

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id): Response
    {
        $Regra = new RegraEntity();
        $Regra->uuid($id);
        $Regra->destruir();

        return new Response(status: 204);
    }

    private function retornoPadrao(RegraEntity $Regra, int $status = 200)
    {
        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity($Regra, lista: [
                'titulo', 'texto', 'empresa'
            ]),
            status: $status
        );
    }
}
