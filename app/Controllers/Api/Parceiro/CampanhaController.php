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
use App\Models\Api\Parceiro\Campanha\CampanhaModel;
use App\Models\Api\Parceiro\Campanha\CampanhaEntity;

final class CampanhaController extends Controller implements
    ControllerBuscarInterface,
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    public function getListar(Request $request): Response
    {
        $Campanha = new CampanhaModel();
        $Campanha->set(lista: $request->dado());

        return mensagemSucesso($Campanha->listarDados());
    }

    public function getBuscar(string $id): Response
    {
        $Campanha = new CampanhaEntity();
        $Campanha->uuid($id);

        return $this->retornoPadrao($Campanha, 200);
    }

    public function postSalvar(Request $request): Response
    {
        $Campanha = new CampanhaEntity();
        $Campanha->set(lista: $request->dado());
        $Campanha->salvar();

        return $this->retornoPadrao($Campanha, 201);
    }

    private function retornoPadrao(CampanhaEntity $Campanha, int $status)
    {
        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity(
                Entity: $Campanha,
                lista: [
                    'parceiro', 'titulo', 'texto', 'data_inicio', 'data_final',
                    'link', 'imagem_desktop', 'imagem_mobile', 'status'
                ]
            ),
            status: $status
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $Campanha = new CampanhaEntity();
        $Campanha->uuid($id);
        $Campanha->set(lista: $request->dado());
        $Campanha->salvar();

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id): Response
    {
        $Campanha = new CampanhaEntity();
        $Campanha->uuid($id);
        $Campanha->destruir();

        return new Response(status: 204);
    }
}
