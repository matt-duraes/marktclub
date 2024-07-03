<?php

namespace App\Controllers\Api;

use App\Classes\SiliumConfig\Ordem;
use App\Models\Api\SiliumConfig\SiliumConfigEntity;
use App\Models\Api\SiliumConfig\SiliumConfigModel;
use Controller\Controller;
use Http\Request;
use Http\Response;
use Modules\Pagina;
use Modules\Quantidade;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerAtualizarInterface;

final class SiliumConfigController extends Controller implements
    ControllerListarInterface,
    ControllerAtualizarInterface
{
    public function getListar(Request $request): Response
    {
        $SiliumConfigModel = new SiliumConfigModel(
            new Pagina($request->pagina),
            new Quantidade($request->quantidade),
            new Ordem($request->ordem)
        );
        return mensagemSucesso($SiliumConfigModel->listarDados());
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $SiliumConfigEntity = new SiliumConfigEntity();
        $SiliumConfigEntity->uuid($id);
        $SiliumConfigEntity->set(lista: $request->dado());
        $SiliumConfigEntity->salvar();
        return new Response(status: 204);
    }

    public function postConfiguracoes(): Response
    {
        $SiliumConfigEntity = new SiliumConfigEntity();
        $SiliumConfigEntity->buscar(['id_admin_empresa', TOKEN['empresa']->id]);

        if (empty($SiliumConfigEntity->id)) {
            $SiliumConfigEntity->buscar(['id_admin_empresa', 1]);
        }
        return mensagemSucesso(
            pegarPropriedadeDaEntity($SiliumConfigEntity, lista: [
                'desconto', 'pontuacao_minima_resgate'
            ])
        );
    }
}
