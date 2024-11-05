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
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;

class SolicitacaoLojaController extends Controller implements
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
        $Solicitacao = new SolicitacaoEntity();
        $Solicitacao->uuid($id);
        return $this->retornoSucesso($Solicitacao);
    }

    /**
     * @param SolicitacaoEntity $Solicitacao
     * @param int               $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoSucesso(SolicitacaoEntity $Solicitacao, int $status = 200): Response
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity($Solicitacao, lista: [
                'nome', 'email', 'telefone', 'mensagem', 'status',
                'quem_indicou', 'origem_clube', 'data_criacao', 'data_atualizacao'
            ]),
            $status,
            Helper::CRIPTOGRAFAR
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
        $Solicitacao = new SolicitacaoModel(
            new Pagina($request->pagina),
            new Quantidade($request->quantidade),
            new Ordem($request->ordem),
            $request->nome,
            new Data($request->data_inicio),
            new Data($request->data_final),
            new Status($request->status)
        );
        $solicitacoes = $Solicitacao->listarDados();
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
        $Solicitacao = new SolicitacaoEntity();
        $Solicitacao->set(lista: $request->dado());
        $Solicitacao->salvar();
        return $this->retornoSucesso($Solicitacao, 201);
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
        $Solicitacao = new SolicitacaoEntity();
        $Solicitacao->uuid($id);
        $Solicitacao->set(lista: $request->dado());
        $Solicitacao->salvar();
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
        $Solicitacao = new SolicitacaoEntity();
        $Solicitacao->uuid($id);
        $Solicitacao->destruir();
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
        $Usuario = new DownloadModel($request);
        $Usuario->set(lista: $request->dado());

        $Download = new ArquivoEntity($Usuario->download(), $request->usuario);
        $Download->salvar();
        return mensagemSucesso([
            'id' => $Download->id
        ], 201);
    }
}
