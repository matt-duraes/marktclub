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
                    'titulo', 'titulo_interno', 'texto', 'imagem_site_desktop', 'imagem_site_mobile',
                    'imagem_restrito_desktop', 'imagem_restrito_mobile', 'link', 'incorporar', 'permissao_restrita',
                    'permissao_site', 'link_restrito', 'data_inicio', 'data_final', 'botao_texto', 'status'
                ]
            )
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $dado = $request->dado();
        if ($request->existe('texto')) {
            $dado['texto'] = $request->getPut('texto', html: false);
        }

        $Live = new LiveEntity();
        $Live->uuid($id);
        $Live->set(lista: $dado);
        $Live->salvar();

        return new Response(status: 204);
    }
}
