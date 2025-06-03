<?php

namespace App\Controllers\Api;

use App\Classes\SolicitacaoLoja\Helper;
use App\Classes\SolicitacaoLoja\Ordem;
use App\Classes\SolicitacaoLoja\Status;
use App\Models\Api\DownloadPrivado\ArquivoEntity;
use App\Models\Api\SolicitacaoLoja\DownloadModel;
use App\Models\Api\SolicitacaoLoja\SolicitacaoEntity;
use App\Models\Api\SolicitacaoLoja\SolicitacaoModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use Modules\Data;
use Modules\Pagina;
use Modules\Quantidade;
use System\Interface\ControllerAtualizarInterface;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;

class SolicitacaoLojaController extends Controller implements
    ControllerBuscarInterface,
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface
{
    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function getBuscar(string $id): Response
    {
        $SolicitacaoEntity = new SolicitacaoEntity();
        $SolicitacaoEntity->uuid($id);
        return $this->retornoSucesso($SolicitacaoEntity);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getListar(Request $request): Response
    {
        $SolicitacaoModel = new SolicitacaoModel(
            new Pagina($request->pagina),
            new Quantidade($request->quantidade),
            new Ordem($request->ordem),
            $request->pesquisa,
            $request->empresa,
            $request->usuario,
            $request->parceiro,
            new Data($request->indicacao_inicio),
            new Data($request->indicacao_final),
            new Data($request->prospeccao_inicio),
            new Data($request->prospeccao_final),
            new Status($request->status)
        );
        $solicitacoes = $SolicitacaoModel->listarDados();
        $solicitacoes->lista = criptografarDado($solicitacoes->lista, Helper::CRIPTOGRAFAR, lista: true);
        return mensagemSucesso($solicitacoes);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $SolicitacaoEntity = new SolicitacaoEntity();
        $SolicitacaoEntity->set(lista: $request->dado());
        $SolicitacaoEntity->salvar();
        return $this->retornoSucesso($SolicitacaoEntity, 201);
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
        $SolicitacaoEntity = new SolicitacaoEntity();
        $SolicitacaoEntity->uuid($id);
        $SolicitacaoEntity->set(lista: $request->dado());
        $SolicitacaoEntity->salvar();
        return new Response(status: 204);
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
            $request->empresa,
            $request->parceiro,
            new Data($request->indicacao_inicio),
            new Data($request->indicacao_final),
            new Status($request->status)
        );
        $ArquivoEntity = new ArquivoEntity($DownloadModel->download(), $request->usuario);
        $ArquivoEntity->salvar();
        return mensagemSucesso([
            'id' => $ArquivoEntity->id
        ], 201);
    }

    /**
     * @param SolicitacaoEntity $solicitacaoEntity
     * @param int               $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoSucesso(SolicitacaoEntity $solicitacaoEntity, int $status = 200): Response
    {
        return mensagemSucesso(pegarPropriedadeDaEntity($solicitacaoEntity, lista: [
            'parceiro', 'parceiro_info', 'nome', 'email', 'telefone', 'mensagem', 'status',
            'quemIndicou', 'origemIndicacao', 'data_criacao', 'data_atualizacao',
            'empresas', 'gestor'
        ]), $status, Helper::CRIPTOGRAFAR);
    }
}
