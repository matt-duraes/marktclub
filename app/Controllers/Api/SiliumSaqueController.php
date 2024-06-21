<?php

namespace App\Controllers\Api;

use App\Classes\Silium\OrdemSaque;
use App\Classes\Silium\StatusSaque;
use App\Classes\Silium\TipoConta;
use App\Models\Api\Silium\SiliumSaqueEntity;
use App\Models\Api\Silium\SiliumSaqueModel;
use Controller\Controller;
use Http\Request;
use Http\Response;
use Modules\Data;
use Modules\Pagina;
use Modules\Quantidade;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerAtualizarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;

final class SiliumSaqueController extends Controller implements
    ControllerBuscarInterface,
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    public function getBuscar(string $id): Response
    {
        $SiliumSaqueEntity = new SiliumSaqueEntity();
        $SiliumSaqueEntity->uuid($id);
        return $this->retornoSucesso($SiliumSaqueEntity);
    }

    public function retornoSucesso(SiliumSaqueEntity $siliumSaqueEntity, int $status = 200): Response
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                Entity: $siliumSaqueEntity,
                lista: [
                    'usuario', 'nome_titular', 'documento_cpf',
                    'email', 'tipo_conta', 'banco', 'agencia', 'conta',
                    'pontuacao', 'status', 'data_criacao', 'data_atualizacao'
                ]
            ),
            $status
        );
    }

    public function getListar(Request $request): Response
    {
        $SiliumSaqueModel = new SiliumSaqueModel(
            new Pagina($request->pagina),
            new Quantidade($request->quantidade),
            new OrdemSaque($request->ordem),
            $request->usuario,
            new TipoConta($request->tipo_conta),
            new Data($request->data_inicio),
            new Data($request->data_final),
            new StatusSaque($request->status)
        );
        return mensagemSucesso($SiliumSaqueModel->listarDados());
    }

    public function postSalvar(Request $request): Response
    {
        $SiliumSaqueEntity = new SiliumSaqueEntity();
        $SiliumSaqueEntity->set(lista: $request->dado());
        $SiliumSaqueEntity->salvar();
        return $this->retornoSucesso($SiliumSaqueEntity, 201);
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $SiliumSaqueEntity = new SiliumSaqueEntity();
        $SiliumSaqueEntity->uuid($id);
        $SiliumSaqueEntity->set(lista: $request->dado());
        $SiliumSaqueEntity->salvar();
        return new Response(status: 204);
    }

    public function deleteDeletar(string $id): Response
    {
        $SiliumSaqueEntity = new SiliumSaqueEntity();
        $SiliumSaqueEntity->uuid($id);
        $SiliumSaqueEntity->destruir();
        return new Response(status: 204);
    }
}
