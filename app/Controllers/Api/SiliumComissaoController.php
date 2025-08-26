<?php

namespace App\Controllers\Api;

use Erro\Excecao;
use Http\Request;
use Modules\Data;
use Http\Response;
use Modules\Pagina;
use Helpers\OrmHelper;
use Modules\Quantidade;
use Controller\Controller;
use App\Classes\SiliumComissao\Ordem;
use App\Classes\SiliumComissao\Status;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use App\Models\Api\SiliumComissao\DownloadModel;
use System\Interface\ControllerDeletarInterface;
use App\Models\Api\DownloadPrivado\ArquivoEntity;
use App\Models\Api\SiliumSaldo\SiliumSaldoEntity;
use System\Interface\ControllerAtualizarInterface;
use App\Models\Api\SiliumComissao\SiliumComissaoModel;
use App\Models\Api\SiliumComissao\SiliumComissaoEntity;

final class SiliumComissaoController extends Controller implements
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
            pagina: new Pagina($request->pagina),
            quantidade: new Quantidade($request->quantidade),
            ordem: new Ordem($request->ordem),
            usuario: $request->usuario,
            parceiro: $request->parceiro,
            dataInicio: new Data($request->data_inicio),
            dataFinal: new Data($request->data_final),
            status: new Status($request->status)
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

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postDownload(Request $request): Response
    {
        $DownloadModel = new DownloadModel(
            $request->campo,
            $request->usuario,
            new Ordem($request->ordem),
            $request->cliente,
            $request->parceiro,
            new Data($request->data_inicio),
            new Data($request->data_final),
            new Status($request->status)
        );
        $ArquivoEntity = new ArquivoEntity($DownloadModel->download(), $request->usuario);
        $ArquivoEntity->salvar();
        return mensagemSucesso([
            'id' => $ArquivoEntity->id
        ], 201);
    }
}
