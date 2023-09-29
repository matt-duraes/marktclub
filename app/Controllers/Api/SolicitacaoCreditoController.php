<?php

namespace App\Controllers\Api;

use App\Classes\SolicitacaoCredito\Operadora;
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
use Modules\Dinheiro;
use Modules\Inteiro;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;

class SolicitacaoCreditoController extends Controller implements
    ControllerBuscarInterface,
    ControllerListarInterface,
    ControllerSalvarInterface
{
    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getSimulacao(Request $request): Response
    {
        $valor = new Dinheiro($request->getJson('valor_total'));
        $Credito = new SimulacaoModel(
            operadora: new Operadora($request->getJson('operadora')),
            tipo: new Tipo($request->getJson('tipo')),
            valor_total: $valor,
            parcela: new Inteiro($request->getJson('parcela'))
        );

        return mensagemSucesso([
            'parcela'       => $request->getJson('parcela'),
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
            operadora: new Operadora($request->getJson('operadora')),
            tipo: new Tipo($request->getJson('tipo')),
            titulo: $request->getJson('titulo')
        );
        return mensagemSucesso($Parcela->listaParcela);
    }

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
            pegarPropriedadeDaEntity(
                $creditoEntity,
                lista: [
                    'operadora', 'tipo', 'valor_total', 'parcela',
                    'valor_parcela', 'data_criacao', 'status', "usuario"
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
        $CreditoModel = new CreditoModel($request);
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

    public function putAtualizar(Request $request, string $id): Response
    {
        $Credito = new CreditoEntity();
        $Credito->uuid($id);
        $Credito->status = new Status($request->getPut('status'));
        $Credito->salvar();

        return new Response(status: 204);
    }
}
