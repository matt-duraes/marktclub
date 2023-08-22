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
use App\Models\Api\SolicitacaoChequeBonus\ChequeBonusModel;
use App\Models\Api\SolicitacaoChequeBonus\ChequeBonusEntity;

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
        $ChequeBonus = new ChequeBonusModel(
            pagina: new Pagina($request->pagina),
            quantidade: new Quantidade($request->quantidade),
            dataCriacaoDe: new Data($request->data_criacao_de),
            dataCriacaoAte: new Data($request->data_criacao_ate),
            status: new Status($request->status),
            empresa: $request->empresa,
            ordem: new Ordem($request->ordem)
        );
        return mensagemSucesso($ChequeBonus->listarDados());
    }

    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function getBuscar(string $id): Response
    {
        $ChequeBonus = new ChequeBonusEntity();
        $ChequeBonus->uuid($id);
        return $this->retornoSucesso($ChequeBonus);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $ChequeBonus = new ChequeBonusEntity();
        $ChequeBonus->set(lista: $request->dado());
        $ChequeBonus->salvar();

        return $this->retornoSucesso($ChequeBonus, 201);
    }

    private function retornoSucesso(ChequeBonusEntity $ChequeBonus, int $status = 200): Response
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $ChequeBonus,
                lista: [
                    'id', 'parceiro', 'usuario', 'modelo', 'versao', 'data_criacao', 'data_atualizacao', 'status'
                ]
            ),
            $status
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $ChequeBonus = new ChequeBonusEntity();
        $ChequeBonus->uuid($id);
        $ChequeBonus->status = new Status($request->status);
        $ChequeBonus->salvar();

        return new Response(status: 204);
    }
}
