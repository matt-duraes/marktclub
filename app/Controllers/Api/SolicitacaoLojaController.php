<?php

namespace App\Controllers\Api;

use Erro\Excecao;
use Http\Request;
use Http\Response;
use Modules\Pagina;
use Modules\Quantidade;
use Controller\Controller;
use App\Classes\SolicitacaoLoja\Ordem;
use App\Classes\SolicitacaoLoja\Status;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerAtualizarInterface;
use App\Models\Api\SolicitacaoLoja\SolicitacaoModel;
use App\Models\Api\SolicitacaoLoja\SolicitacaoEntity;

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
        return mensagemSucesso($Solicitacao->listarDados());
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

    private function retornoSucesso(SolicitacaoEntity $Solicitacao, int $status = 200): Response
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity($Solicitacao, lista: [
                'nome', 'email', 'telefone', 'mensagem', 'origem', 'data_criacao', 'data_atualizacao', 'status'
            ]),
            $status
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
