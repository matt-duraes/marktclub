<?php

namespace App\Controllers\Api;

use Erro\Excecao;
use Http\Request;
use Http\Response;
use Modules\Inteiro;
use Modules\Dinheiro;
use Controller\Controller;
use App\Classes\SolicitacaoCredito\Tipo;
use App\Classes\SolicitacaoCredito\Operadora;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use App\Models\Api\SolicitacaoCredito\CreditoModel;
use App\Models\Api\SolicitacaoCredito\ParcelaModel;
use App\Models\Api\SolicitacaoCredito\CreditoEntity;
use App\Models\Api\SolicitacaoCredito\SimulacaoModel;

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
        $valor = new Dinheiro($request->valor_total);
        $Credito = new SimulacaoModel(
            operadora: new Operadora($request->operadora),
            tipo: new Tipo($request->tipo),
            valor_total: $valor,
            parcela: new Inteiro($request->parcela)
        );

        return mensagemSucesso([
            'parcela'       => $request->parcela,
            'valor_parcela' => $Credito->valorParcela->decimal(),
            'valor_total'   => $valor->decimal(),
        ]);
    }

    public function getParcela(Request $request): Response
    {
        $Parcela = new ParcelaModel(
            operadora: new Operadora($request->operadora),
            tipo: new Tipo($request->tipo),
            titulo: $request->titulo
        );
        return mensagemSucesso($Parcela->listaParcela);
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
                    'valor_parcela', 'data_criacao', 'status'
                ]
            ),
            $status
        );
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
}
