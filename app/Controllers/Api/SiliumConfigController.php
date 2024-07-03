<?php

namespace App\Controllers\Api;

use App\Models\Api\SiliumConfig\SiliumConfigEntity;
use App\Models\Api\SiliumConfig\SiliumConfigModel;
use Controller\Controller;
use Http\Request;
use Http\Response;
use Modules\Pagina;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerAtualizarInterface;

final class SiliumConfigController extends Controller implements
    ControllerListarInterface,
    ControllerAtualizarInterface
{
    public function getListar(Request $request): Response
    {
        $SiliumConfigModel = new SiliumConfigModel(
            new Pagina($request->pagina)
        );
        return mensagemSucesso($SiliumConfigModel->listarSelect());
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $SiliumConfigEntity = new SiliumConfigEntity();
        $SiliumConfigEntity->uuid($id);
        $SiliumConfigEntity->set(lista: $request->dado());
        $SiliumConfigEntity->salvar();
        return new Response(status: 204);
    }
}
