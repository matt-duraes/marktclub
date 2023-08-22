<?php

namespace App\Controllers\Api;

use Erro\Excecao;
use Http\Request;
use Modules\Data;
use Http\Response;
use Modules\Pagina;
use Modules\Quantidade;
use Controller\Controller;
use App\Classes\Solicitacao\Status;
use App\Classes\SolicitacaoChequeBonus\Ordem;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerAtualizarInterface;
use App\Models\Api\SolicitacaoChequeBonus\DeclaracaoModel;
use App\Models\Api\SolicitacaoChequeBonus\DeclaracaoEntity;

class SolicitacaoChequeBonusController extends Controller implements
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
            pagina: new Pagina($request->pagina),
            quantidade: new Quantidade($request->quantidade),
            dataCriacaoDe: new Data($request->data_criacao_de),
            dataCriacaoAte: new Data($request->data_criacao_ate),
            status: new Status($request->status),
            empresa: $request->empresa,
            ordem: new Ordem($request->ordem)
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
                    'id', 'parceiro', 'usuario', 'modelo', 'versao', 'data_criacao', 'data_atualizacao', 'status'
                ]
            ),
            $status
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $Declaracao = new DeclaracaoEntity();
        $Declaracao->uuid($id);
        $Declaracao->status = new Status($request->status);
        $Declaracao->salvar();

        return new Response(status: 204);
    }
}
