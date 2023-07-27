<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Classes\Geral\Status;
use App\Classes\ParceiroEasylive\Tipo;
use App\Classes\ParceiroEasylive\Ordem;
use App\Models\Api\ParceiroEasylive\LojaModel;
use App\Models\Api\ParceiroEasylive\LojaEntity;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerAtualizarInterface;

final class ParceiroEasyliveController extends Controller implements
    ControllerListarInterface,
    ControllerBuscarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    public function getListar(Request $request): Response
    {
        $Loja = new LojaModel(
            pagina: $request->pagina,
            tipo: new Tipo($request->tipo),
            status: new Status($request->status),
            ordem: new Ordem($request->ordem)
        );
        return mensagemSucesso($Loja->listarDados());
    }

    public function getBuscar(string $id): Response
    {
        $LojaEntity = new LojaEntity();
        $LojaEntity->uuid($id);
        return $this->retornoPadrao($LojaEntity);
    }

    public function postSalvar(Request $request): Response
    {
        $LojaEntity = new LojaEntity();
        $LojaEntity->set(lista: $request->dado());
        $LojaEntity->salvar();

        return $this->retornoPadrao($LojaEntity, 201);
    }

    private function retornoPadrao($LojaEntity, $status = 200): Response
    {
        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity(
                $LojaEntity,
                lista: ['id', 'empresa', 'titulo', 'imagem', 'link_imagem', 'tipo', 'data_validade', 'status']
            ),
            status: $status
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $LojaEntity = new LojaEntity();
        $LojaEntity->uuid($id);
        $LojaEntity->set(lista: $request->dado());
        $LojaEntity->salvar();
        return new Response(status: 204);
    }

    public function deleteDeletar(string $id): Response
    {
        $LojaEntity = new LojaEntity();
        $LojaEntity->uuid($id);
        $LojaEntity->destruir();
        return new Response(status: 204);
    }
}
