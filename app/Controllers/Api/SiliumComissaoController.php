<?php

namespace App\Controllers\Api;

use App\Classes\SiliumComissao\Ordem;
use App\Classes\SiliumComissao\Status;
use App\Models\Api\SiliumComissao\SiliumComissaoEntity;
use App\Models\Api\SiliumComissao\SiliumComissaoModel;
use App\Models\Api\SiliumSaldo\SiliumSaldoEntity;
use Controller\Controller;
use Erro\Excecao;
use Helpers\OrmHelper;
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

final class SiliumComissaoController extends Controller implements
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerBuscarInterface,
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
        $SiliumComissaoEntity = new SiliumComissaoEntity();
        $SiliumComissaoEntity->uuid($id);
        return $this->retornoSucesso($SiliumComissaoEntity);
    }

    /**
     * @param SiliumComissaoEntity $siliumComissaoEntity
     * @param int                  $status
     *
     * @return Response
     * @throws Excecao
     */
    public function retornoSucesso(SiliumComissaoEntity $siliumComissaoEntity, int $status = 200): Response
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $siliumComissaoEntity,
                lista: [
                    'empresa', 'usuario', 'parceiro', 'valor_compra',
                    'comissao_usuario', 'pontuacao', 'data_compra', 'status',
                    'data_criacao', 'data_atualizacao'
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
        $SiliumComissaoModel = new SiliumComissaoModel(
            new Pagina($request->pagina),
            new Quantidade($request->quantidade),
            new Ordem($request->ordem),
            $request->usuario,
            $request->parceiro,
            new Data($request->data_inicio),
            new Data($request->data_final),
            new Status($request->status)
        );
        return mensagemSucesso($SiliumComissaoModel->listarDados());
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $SiliumComissaoEntity = new SiliumComissaoEntity();
        $SiliumComissaoEntity->set(lista: $request->dado());
        $SiliumComissaoEntity->salvar();
        return $this->retornoSucesso($SiliumComissaoEntity, 201);
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
        $SiliumComissaoEntity = new SiliumComissaoEntity();
        $SiliumComissaoEntity->uuid($id);
        $SiliumComissaoEntity->set(lista: $request->dado());
        $SiliumComissaoEntity->salvar();
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
        $SiliumComissaoEntity = new SiliumComissaoEntity();
        $SiliumComissaoEntity->uuid($id);
        $SiliumComissaoEntity->destruir();
        return new Response(status: 204);
    }

    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function getSaldo(string $id): Response
    {
        $OrmHelper = new OrmHelper(TABELA_USUARIO_CLIENTE);
        $idUsuario = $OrmHelper->pegarIdPeloUuid($id);

        $SiliumSaldoEntity = new SiliumSaldoEntity();
        $SiliumSaldoEntity->buscar([
            'id_usuario_cliente', $idUsuario
        ], false);

        if (empty($SiliumSaldoEntity->id)) {
            $SiliumSaldoEntity->saldo_silium = 0;
        }

        return mensagemSucesso(
            pegarPropriedadeDaEntity($SiliumSaldoEntity, lista: [
                'saldo_silium'
            ])
        );
    }
}
