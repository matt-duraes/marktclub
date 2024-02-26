<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Api\AlbumFoto\FotoModel;
use App\Models\Api\AlbumFoto\FotoEntity;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerAtualizarInterface;

final class AlbumFotoController extends Controller implements
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    public function getListar(Request $request): Response
    {
        $Foto = new FotoModel();
        $Foto->set(lista: $request->dado());

        return mensagemSucesso($Foto->listarDados());
    }

    public function postSalvar(Request $request): Response
    {
        $Foto = new FotoEntity();
        $Foto->set(lista: $request->dado());
        $Foto->salvar();

        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity(
                Entity: $Foto,
                lista: [
                    'titulo', 'imagem', 'status'
                ]
            ),
            status: 201
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $Foto = new FotoEntity();
        $Foto->uuid($id);
        $Foto->set(lista: $request->dado());
        $Foto->salvar();

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id): Response
    {
        $Foto = new FotoEntity();
        $Foto->uuid($id);
        $Foto->destruir();

        return new Response(status: 204);
    }
}
