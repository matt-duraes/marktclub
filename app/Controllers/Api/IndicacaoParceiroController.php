<?php

namespace App\Controllers\Api;

use Http\Request;
use Erro\Excecao;
use Http\Response;
use Controller\Controller;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use App\Models\Api\IndicacaoParceiro\IndicacaoParceiroEntity;
use App\Models\Api\IndicacaoParceiro\IndicacaoParceiroModel;

class IndicacaoParceiroController extends Controller implements
    ControllerBuscarInterface,
    ControllerListarInterface,
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
        $IndicacaoParceiro = new IndicacaoParceiroEntity();
        $IndicacaoParceiro->uuid($id);

        return $this->retornoSucesso($IndicacaoParceiro);
    }

    /**
     * @param  Request  $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getListar(Request $request): Response
    {
        $IndicacaoParceiro = new IndicacaoParceiroModel($request);
        return mensagemSucesso($IndicacaoParceiro->listarDados());
    }

    /**
    * @param  IndicacaoParceiroEntity  $indicacaoParceiroEntity
    *
    * @return Response
    * @throws Excecao
    */
    public function postSalvar(Request $request): Response
    {
        $IndicacaoParceiro = new IndicacaoParceiroEntity();
        $IndicacaoParceiro->set(lista: $request->dado());
        $IndicacaoParceiro->salvar();

        return $this->retornoSucesso($IndicacaoParceiro, 201);
    }


    /**
     * @param  IndicacaoParceiroEntity  $indicacaoParceiroEntity
     * @param  int            $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoSucesso(IndicacaoParceiroEntity $indicacaoParceiroEntity, int $status = 200): Response
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $indicacaoParceiroEntity,
                lista: [
                    'parceiro', 'telefone', 'email', 'mensagem'
                ]
            ),
            $status
        );
    }
}
