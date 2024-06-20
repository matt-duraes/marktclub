<?php

namespace App\Controllers\Api;

use App\Classes\Silium\OrdemComissao;
use App\Classes\Silium\StatusComissao;
use App\Models\Api\Silium\SiliumComissaoEntity;
use App\Models\Api\Silium\SiliumComissaoModel;
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

final class SiliumComissaoController extends Controller implements
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerBuscarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    public function getBuscar(string $id): Response
    {
        $SiliumComissaoEntity = new SiliumComissaoEntity();
        $SiliumComissaoEntity->uuid($id);
        return $this->retornoSucesso($SiliumComissaoEntity);
    }

    public function getListar(Request $request): Response
    {
        $SiliumComissaoModel = new SiliumComissaoModel(
            new Pagina($request->pagina),
            new Quantidade($request->quantidade),
            new OrdemComissao($request->ordem),
            $request->usuario,
            $request->parceiro,
            new Data($request->data_inicio),
            new Data($request->data_final),
            new StatusComissao($request->status)
        );
        return mensagemSucesso($SiliumComissaoModel->listarDados());
    }

    public function postSalvar(Request $request): Response
    {
        $SiliumComissaoEntity = new SiliumComissaoEntity();
        $SiliumComissaoEntity->set(lista: $request->dado());
        $SiliumComissaoEntity->salvar();
        return $this->retornoSucesso($SiliumComissaoEntity, 201);
    }

    public function retornoSucesso(SiliumComissaoEntity $siliumComissaoEntity, int $status = 200): Response
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $siliumComissaoEntity,
                lista: [
                    'usuario', 'parceiro', 'valor_compra',
                    'comissao_usuario', 'pontuacao', 'data_compra', 'status',
                    'data_criacao', 'data_atualizacao'
                ]
            ),
            $status
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $SiliumComissaoEntity = new SiliumComissaoEntity();
        $SiliumComissaoEntity->uuid($id);
        $SiliumComissaoEntity->set(lista: $request->dado());
        $SiliumComissaoEntity->salvar();
        return new Response(status: 204);
    }

    public function deleteDeletar(string $id): Response
    {
        $SiliumComissaoEntity = new SiliumComissaoEntity();
        $SiliumComissaoEntity->uuid($id);
        $SiliumComissaoEntity->destruir();
        return new Response(status: 204);
    }
}
