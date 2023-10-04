<?php

namespace App\Controllers\Api;

use App\Classes\SolicitacaoCredito\Operadora;
use App\Classes\SolicitacaoCredito\Ordem;
use App\Classes\SolicitacaoCredito\Status;
use App\Classes\SolicitacaoCredito\Tipo;
use App\Models\Api\SolicitacaoCredito\CreditoEntity;
use App\Models\Api\SolicitacaoCredito\CreditoModel;
use App\Models\Api\SolicitacaoCredito\ParcelaModel;
use App\Models\Api\SolicitacaoCredito\SimulacaoModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use Modules\Data;
use Modules\Dinheiro;
use Modules\Inteiro;
use Modules\Pagina;
use Modules\Quantidade;
use System\Interface\ControllerAtualizarInterface;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;

class SolicitacaoCreditoController extends Controller implements
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
        $CreditoEntity = new CreditoEntity();
        $CreditoEntity->uuid($id);
        return $this->retornoSucesso($CreditoEntity);
    }

    /**
     * @param CreditoEntity $creditoEntity
     * @param int           $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoSucesso(CreditoEntity $creditoEntity, int $status = 200): Response
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity($creditoEntity, lista: [
                'operadora', 'tipo', 'valor_total', 'parcela',
                'valor_parcela', 'data_criacao', 'status', 'usuario', 'data_atualizacao'
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
    public function getListar(Request $request): Response
    {
        $CreditoModel = new CreditoModel(
            new Pagina($request->pagina),
            new Quantidade($request->quantidade),
            new Ordem($request->ordem),
            $request->nome,
            new Operadora($request->operadora),
            new Tipo($request->tipo),
            new Data($request->data_inicio),
            new Data($request->data_final),
            new Status($request->status)
        );
        return mensagemSucesso($CreditoModel->listarDados());
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $Credito = new CreditoEntity();
        $Credito->set(lista: $request->dado());
        $Credito->salvar();
        return $this->retornoSucesso($Credito, 201);
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
        $Credito = new CreditoEntity();
        $Credito->uuid($id);
        $Credito->set(lista: $request->dado());
        $Credito->salvar();
        return new Response(status: 204);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getSimulacao(Request $request): Response
    {
        $valor = new Dinheiro($request->valor_total);
        $Credito = new SimulacaoModel(
            new Operadora($request->operadora),
            new Tipo($request->tipo),
            $valor,
            new Inteiro($request->parcela)
        );
        return mensagemSucesso([
            'parcela'       => $request->parcela,
            'valor_parcela' => $Credito->valorParcela->decimal(),
            'valor_total'   => $valor->decimal()
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getParcela(Request $request): Response
    {
        $Parcela = new ParcelaModel(
            new Operadora($request->operadora),
            new Tipo($request->tipo),
            $request->titulo
        );
        return mensagemSucesso($Parcela->listaParcela);
    }
}
