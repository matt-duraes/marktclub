<?php

namespace App\Controllers\Api;

use App\Models\Api\Saude\Simulacao\SimulacaoEntity;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerSalvarInterface;

class SaudeSimulacaoController extends Controller implements
    ControllerBuscarInterface,
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
        $SimulacaoEntity = new SimulacaoEntity();
        $SimulacaoEntity->uuid($id);
        return mensagemSucesso(
            pegarPropriedadeDaEntity($SimulacaoEntity, lista: [
                'data_nascimento', 'quantidade_dependentes', 'operadora', 'acomodacao',
                'regiao', 'valor_titular', 'valor_dependentes', 'valor_total', 'plano', 'status'
            ])
        );
    }

    /**
     * @param  Request  $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $SimulacaoEntity = new SimulacaoEntity($request);
        $SimulacaoEntity->salvar();
        return mensagemSucesso($SimulacaoEntity->retorno(), 201);
    }
}
