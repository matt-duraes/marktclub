<?php

namespace App\Controllers\Api;

use App\Classes\Silium\OrdemDeposito;
use App\Classes\Silium\StatusDeposito;
use App\Models\Api\Silium\SiliumDepositoEntity;
use App\Models\Api\Silium\SiliumDepositoModel;
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

final class SiliumDepositoController extends Controller implements
    ControllerBuscarInterface,
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    public function getBuscar(string $id): Response
    {
        $SiliumDepositoEntity = new SiliumDepositoEntity();
        $SiliumDepositoEntity->uuid($id);
        return $this->retornoSucesso($SiliumDepositoEntity);
    }

    public function retornoSucesso(SiliumDepositoEntity $SiliumDepositoEntity, int $status = 200): Response
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                Entity: $SiliumDepositoEntity,
                lista: [
                    'empresa','usuario', 'nome_titular', 'documento_cpf',
                    'tipo_conta', 'banco', 'agencia', 'conta', 'valor',
                    'pontuacao', 'data_deposito', 'documento_anexo',
                    'status', 'data_criacao', 'data_atualizacao'
                ]
            ),
            $status
        );
    }

    public function getListar(Request $request): Response
    {
        $SiliumDeposito = new SiliumDepositoModel(
            new Pagina($request->pagina),
            new Quantidade($request->quantidade),
            new OrdemDeposito($request->ordem),
            $request->empresa,
            $request->usuario,
            new Data($request->data_inicio),
            new Data($request->data_final),
            new StatusDeposito($request->status)
        );
        return mensagemSucesso($SiliumDeposito->listarDados());
    }

    public function postSalvar(Request $request): Response
    {
        $SiliumDepositoEntity = new SiliumDepositoEntity();
        $SiliumDepositoEntity->set(lista: $request->dado());
        $SiliumDepositoEntity->salvar();
        return $this->retornoSucesso($SiliumDepositoEntity, 201);
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $SiliumDepositoEntity = new SiliumDepositoEntity();
        $SiliumDepositoEntity->uuid($id);
        $SiliumDepositoEntity->set(lista: $request->dado());
        $SiliumDepositoEntity->salvar();
        return new Response(status: 204);
    }

    public function deleteDeletar(string $id): Response
    {
        $SiliumDepositoEntity = new SiliumDepositoEntity();
        $SiliumDepositoEntity->uuid($id);
        $SiliumDepositoEntity->destruir();
        return new Response(status: 204);
    }
}
