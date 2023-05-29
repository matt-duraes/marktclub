<?php

namespace App\Controllers\Api;

use App\Models\Api\SolicitacaoCredito\CreditoEntity;
use App\Models\Api\SolicitacaoCredito\CreditoModel;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use System\Interface\ControllerAtualizarInterface;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;

class SolicitacaoCreditoController implements
    ControllerBuscarInterface,
    ControllerListarInterface,
    ControllerAtualizarInterface,
    ControllerSalvarInterface
{
    /**
     * @param  string  $id
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
     * @param  CreditoEntity  $creditoEntity
     * @param  int            $status
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
                    'cod', 'vinculo', 'tipo', 'status', 'data_criacao'
                ]
            ),
            $status
        );
    }

    /**
     * @param  Request  $request
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
     * @param  Request  $request
     * @param  string   $id
     *
     * @return Response
     * @throws Excecao
     */
    public function putAtualizar(Request $request, string $id): Response
    {
        $CreditoEntity = new CreditoEntity();
        $CreditoEntity->uuid($id);
        $CreditoEntity->set(lista: $request->dado());
        $CreditoEntity->salvar();

        return $this->retornoSucesso($CreditoEntity);
    }

    /**
     * @param  Request  $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $CreditoEntity = new CreditoEntity($request);
        $CreditoEntity->set(lista: $request->dado());
        $CreditoEntity->salvar();

        return $this->retornoSucesso($CreditoEntity, 201);
    }
}
