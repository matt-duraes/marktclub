<?php

namespace App\Controllers\Api;

use App\Classes\SiliumDeposito\Ordem;
use App\Classes\SiliumDeposito\Status;
use App\Classes\SiliumDeposito\TipoConta;
use App\Classes\SiliumDeposito\TipoOperacao;
use App\Classes\SiliumDeposito\TipoResgate;
use App\Models\Api\SiliumDeposito\SiliumDepositoEntity;
use App\Models\Api\SiliumDeposito\SiliumDepositoModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use Modules\Data;
use Modules\Pagina;
use Modules\Quantidade;
use System\Interface\ControllerAtualizarInterface;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;

final class SiliumDepositoController extends Controller implements
    ControllerBuscarInterface,
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function getBuscar(string $id): Response
    {
        $SiliumDepositoEntity = new SiliumDepositoEntity();
        $SiliumDepositoEntity->uuid($id);
        return $this->retornoSucesso($SiliumDepositoEntity);
    }

    /**
     * @param SiliumDepositoEntity $SiliumDepositoEntity
     * @param int                  $status
     *
     * @return Response
     * @throws Excecao
     */
    public function retornoSucesso(SiliumDepositoEntity $SiliumDepositoEntity, int $status = 200): Response
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $SiliumDepositoEntity,
                lista: [
                    'usuario', 'nome_titular', 'documento_cpf', 'email',
                    'tipo_conta', 'banco', 'agencia', 'conta', 'pontuacao',
                    'valor', 'data_deposito', 'documento_anexo', 'status',
                    'tipo_operacao', 'tipo_resgate', 'data_criacao',
                    'data_atualizacao'
                ]
            ),
            $status
        );
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getListar(Request $request): Response
    {
        $SiliumDepositoModel = new SiliumDepositoModel(
            new Pagina($request->pagina),
            new Quantidade($request->quantidade),
            new Ordem($request->ordem),
            $request->usuario,
            new TipoConta($request->tipo_conta),
            new TipoOperacao($request->tipo_operacao),
            new TipoResgate($request->tipo_resgate),
            new Data($request->data_inicio),
            new Data($request->data_final),
            new Status($request->status)
        );
        return mensagemSucesso($SiliumDepositoModel->listarDados());
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $SiliumDepositoEntity = new SiliumDepositoEntity();
        $SiliumDepositoEntity->set(lista: $request->dado());
        $SiliumDepositoEntity->salvar();
        return $this->retornoSucesso($SiliumDepositoEntity, 201);
    }

    /**
     * @param Request $request
     * @param string  $id
     *
     * @return Response
     * @throws Excecao
     */
    public function putAtualizar(Request $request, string $id): Response
    {
        $SiliumDepositoEntity = new SiliumDepositoEntity();
        $SiliumDepositoEntity->uuid($id);
        $SiliumDepositoEntity->set(lista: $request->dado());
        $SiliumDepositoEntity->salvar();
        return new Response(status: 204);
    }

    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function deleteDeletar(string $id): Response
    {
        $SiliumDepositoEntity = new SiliumDepositoEntity();
        $SiliumDepositoEntity->uuid($id);
        $SiliumDepositoEntity->destruir();
        return new Response(status: 204);
    }
}
