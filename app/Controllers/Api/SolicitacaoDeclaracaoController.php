<?php

namespace App\Controllers\Api;

use App\Classes\Solicitacao\Status;
use App\Classes\SolicitacaoDeclaracao\Ordem;
use App\Models\Api\SolicitacaoDeclaracao\DeclaracaoEntity;
use App\Models\Api\SolicitacaoDeclaracao\DeclaracaoModel;
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

class SolicitacaoDeclaracaoController extends Controller implements
    ControllerBuscarInterface,
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface
{
    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getListar(Request $request): Response
    {
        $Declaracao = new DeclaracaoModel(
            pagina: new Pagina($request->getJson('pagina')),
            quantidade: new Quantidade($request->getJson('quantidade')),
            dataCriacaoDe: new Data($request->getJson('data_criacao_de')),
            dataCriacaoAte: new Data($request->getJson('data_criacao_ate')),
            status: new Status($request->getJson('status')),
            empresa: $request->getJson('empresa'),
            ordem: new Ordem($request->getJson('ordem'))
        );
        return mensagemSucesso($Declaracao->listarDados());
    }

    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function getBuscar(string $id): Response
    {
        $Declaracao = new DeclaracaoEntity();
        $Declaracao->uuid($id);
        return $this->retornoSucesso($Declaracao);
    }

    /**
     * @param DeclaracaoEntity $Declaracao
     * @param int              $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoSucesso(DeclaracaoEntity $Declaracao, int $status = 200): Response
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $Declaracao,
                lista: [
                    'id', 'parceiro', 'usuario', 'modelo', 'versao',
                    'data_criacao', 'data_atualizacao', 'status'
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
    public function postSalvar(Request $request): Response
    {
        $Declaracao = new DeclaracaoEntity();
        $Declaracao->set(lista: $request->dado());
        $Declaracao->salvar();

        return $this->retornoSucesso($Declaracao, 201);
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
        $Declaracao = new DeclaracaoEntity();
        $Declaracao->uuid($id);
        $Declaracao->status = new Status($request->getPut('status'));
        $Declaracao->salvar();

        return new Response(status: 204);
    }
}
