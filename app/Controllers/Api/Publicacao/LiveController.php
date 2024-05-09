<?php

namespace App\Controllers\Api\Publicacao;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Api\Publicacao\Live\LiveEntity;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerAtualizarInterface;

final class LiveController extends Controller implements
    ControllerBuscarInterface,
    ControllerAtualizarInterface
{
    public function getBuscar(string $id): Response
    {
        $Live = new LiveEntity();
        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity(
                Entity: $Live,
                lista: [
                    'titulo', 'titulo_interno', 'texto', 'imagem_site', 'imagem_restrito', 'link',
                    'permissao_restrita', 'permissao_site', 'link_restrito', 'data_inicio', 'data_final', 'status'
                ]
            )
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $Live = new LiveEntity();
        $Live->uuid($id);
        $Live->set(lista: $request->dado());
        $Live->salvar();

        return new Response(status: 204);
    }
}
