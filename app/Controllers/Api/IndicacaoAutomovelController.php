<?php

namespace App\Controllers\Api;

use Http\Request;
use Erro\Excecao;
use Http\Response;
use Controller\Controller;
use System\Interface\ControllerSalvarInterface;
use App\Models\Api\IndicacaoAutomovel\IndicacaoAutomovelEntity;

class IndicacaoAutomovelController extends Controller implements
    ControllerSalvarInterface
{
    /**
     * @param IndicacaoAutomovelEntity $indicacaoAutomovelEntity
    *
     * @return Response
     * @throws Excecao
    */
    public function postSalvar(Request $request): Response
    {
        $IndicacaoAutomovel = new IndicacaoAutomovelEntity();
        $IndicacaoAutomovel->set(lista: $request->dado());
        $IndicacaoAutomovel->salvar();

        return $this->retornoSucesso($IndicacaoAutomovel, 201);
    }

    /**
     * @param IndicacaoAutomovelEntity $indicacaoAutomovelEntity
     * @param int                      $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoSucesso(IndicacaoAutomovelEntity $indicacaoAutomovelEntity, int $status = 200): Response
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $indicacaoAutomovelEntity,
                lista: [
                    'produto', 'modelo', 'versao', 'cor', 'cidade', 'mensagem'
                ]
            ),
            $status
        );
    }
}
