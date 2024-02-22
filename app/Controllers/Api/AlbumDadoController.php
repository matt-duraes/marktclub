<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use App\Models\Api\AlbumDado\AlbumModel;
use App\Models\Api\AlbumDado\AlbumEntity;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerAtualizarInterface;
use System\Interface\ControllerDeletarInterface;
use Controller\Controller;

final class AlbumDadoController extends Controller implements
    ControllerListarInterface,
    ControllerBuscarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    public function getListar(Request $request): Response
    {
        $Album = new AlbumModel();
        $Album->set(lista: $request->dado());

        return mensagemSucesso($Album->listarDados());
    }

    public function getBuscar(string $id): Response
    {
        $Album = new AlbumEntity();
        $Album->uuid($id);

        return $this->retornoPadrao(Album: $Album, status: 200);
    }

    public function postSalvar(Request $request): Response
    {
        $Album = new AlbumEntity();
        $Album->set(lista: $request->dado());
        $Album->salvar();

        return $this->retornoPadrao(Album: $Album, status: 201);
    }

    private function retornoPadrao(AlbumEntity $Album, int $status): Response
    {
        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity(
                Entity: $Album,
                lista: []
            ),
            status: $status
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $Album = new AlbumEntity();
        $Album->uuid($id);
        $Album->set(lista: $request->dado());
        $Album->salvar();

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id): Response
    {
        $Album = new AlbumEntity();
        $Album->uuid($id);
        $Album->destruir();

        return new Response(status: 204);
    }
}
