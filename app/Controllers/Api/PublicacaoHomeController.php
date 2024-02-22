<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Api\PublicacaoHome\HomeEntity;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerAtualizarInterface;

final class PublicacaoHomeController extends Controller implements
    ControllerBuscarInterface,
    ControllerAtualizarInterface
{
    public function getBuscar(string $id): Response
    {
        $Home = new HomeEntity();
        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity(
                Entity: $Home,
                lista: ['noticia_1', 'noticia_2', 'noticia_3']
            )
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $Home = new HomeEntity();
        $Home->uuid($id);
        $Home->set(lista: $request->dado());
        $Home->salvar();

        return new Response(status: 204);
    }
}
