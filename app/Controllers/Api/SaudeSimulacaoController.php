<?php

namespace App\Controllers\Api;

use Erro\Excecao;
use Http\Request;
use Http\Response;
use Controller\Controller;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerSalvarInterface;
use App\Models\Api\Saude\Simulacao\SimulacaoEntity;

final class SaudeSimulacaoController extends Controller implements
    ControllerBuscarInterface,
    ControllerSalvarInterface
{
    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function getBuscar(string $id): Response
    {
        $SimulacaoEntity = new SimulacaoEntity();
        $SimulacaoEntity->uuid($id);
        return $this->retornoPadrao($SimulacaoEntity);
    }

    /**
     * @param SimulacaoEntity $simulacaoEntity
     * @param int             $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoPadrao(SimulacaoEntity $simulacaoEntity, int $status = 200): Response
    {
        $Simulacao = pegarPropriedadeDaEntity($simulacaoEntity, lista: [
            'titular', 'quantidade_dependente', 'operadora',
            'acomodacao', 'regiao', 'valor_titular', 'lista_dependente',
            'valor_total', 'plano', 'status'
        ]);
        return mensagemSucesso($Simulacao, $status);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $SimulacaoEntity = new SimulacaoEntity($request);
        $SimulacaoEntity->salvar();
        return $this->retornoPadrao($SimulacaoEntity, 201);
    }
}
