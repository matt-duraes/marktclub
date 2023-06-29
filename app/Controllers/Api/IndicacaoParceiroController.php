<?php

namespace App\Controllers\Api;

use App\Models\Api\IndicacaoParceiro\IndicacaoParceiroEntity;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use System\Interface\ControllerSalvarInterface;

class IndicacaoParceiroController extends Controller implements
    ControllerSalvarInterface
{
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
