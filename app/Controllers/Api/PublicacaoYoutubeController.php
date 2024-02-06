<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use App\Models\Api\PublicacaoYoutube\YoutubeModel;
use System\Interface\ControllerAtualizarInterface;
use App\Models\Api\PublicacaoYoutube\YoutubeEntity;

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
        $Youtube->idSlug($id);

        return $this->retornoPadrao(Youtube: $Youtube, status: 200);
    }

    public function postSalvar(Request $request): Response
    {
        $dado = $request->dado();
        $dado['texto'] = $request->getPost('texto', html: false);
        $Youtube = new YoutubeEntity();
        $Youtube->set(lista: $dado);
        $Youtube->salvar();

        return $this->retornoPadrao(Youtube: $Youtube, status: 201);
    }

    private function retornoPadrao(YoutubeEntity $Youtube, int $status): Response
    {
        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity(
                Entity: $Youtube,
                lista: [
                    'titulo', 'texto', 'header_titulo', 'header_descricao', 'header_tag',
                    'video', 'data_inicio', 'data_final', 'publicado', 'url', 'status'
                ]
            ),
            status: $status
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $dado = $request->dado();
        if (!$request->vazio('texto')) {
            $dado['texto'] = $request->getPut('texto', html: false);
        }

        $Youtube = new YoutubeEntity();
        $Youtube->uuid($id);
        $Youtube->set(lista: $dado);
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
