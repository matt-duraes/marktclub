<?php

namespace App\Controllers\Api;

use App\Classes\SolicitacaoLoja\Helper;
use App\Classes\SolicitacaoLoja\Ordem;
use App\Classes\SolicitacaoLoja\Status;
use App\Models\Api\SolicitacaoLoja\SolicitacaoEntity;
use App\Models\Api\SolicitacaoLoja\SolicitacaoModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use Modules\Pagina;
use Modules\Quantidade;
use System\Interface\ControllerAtualizarInterface;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;

class SolicitacaoLojaController extends Controller implements
    ControllerListarInterface,
    ControllerBuscarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getListar(Request $request): Response
    {
        $Solicitacao = new SolicitacaoModel(
            pagina: new Pagina($request->pagina),
            quantidade: new Quantidade($request->quantidade),
            ordem: new Ordem($request->ordem),
            status: new Status($request->status)
        );

        $dado = $Solicitacao->listarDados();
        $dado->lista = criptografarDado($dado->lista, Helper::CRIPTOGRAFAR, lista: true);
        return mensagemSucesso($dado);
    }

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
                'nome', 'email', 'telefone', 'mensagem',
                'origem', 'status', 'quem_indicou',
                'data_criacao', 'data_atualizacao'
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
}
