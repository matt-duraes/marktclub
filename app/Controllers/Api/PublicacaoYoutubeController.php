<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use App\Models\Api\PublicacaoYoutube\YoutubeModel;
use App\Models\Api\PublicacaoYoutube\YoutubeEntity;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerAtualizarInterface;
use System\Interface\ControllerDeletarInterface;
use Controller\Controller;

final class PublicacaoYoutubeController extends Controller implements
    ControllerListarInterface,
    ControllerBuscarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    public function getListar(Request $request): Response
    {
        $Youtube = new YoutubeModel();
        $Youtube->set(lista: $request->dado());

        return mensagemSucesso($Youtube->listarDados());
    }

    public function getBuscar(string $id): Response
    {
        $Youtube = new YoutubeEntity();
        $Youtube->uuid($id);

        return $this->retornoPadrao(Youtube: $Youtube, status: 200);
    }

    public function postSalvar(Request $request): Response
    {
        $Youtube = new YoutubeEntity();
        $Youtube->set(lista: $request->dado());
        $Youtube->salvar();

        return $this->retornoPadrao(Youtube: $Youtube, status: 201);
    }

    private function retornoPadrao(YoutubeEntity $Youtube, int $status): Response
    {
        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity(
                Entity: $Youtube,
                lista: []
            ),
            status: $status
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $Youtube = new YoutubeEntity();
        $Youtube->uuid($id);
        $Youtube->set(lista: $request->dado());
        $Youtube->salvar();

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id): Response
    {
        $Youtube = new YoutubeEntity();
        $Youtube->uuid($id);
        $Youtube->destruir();

        return new Response(status: 204);
    }
}
