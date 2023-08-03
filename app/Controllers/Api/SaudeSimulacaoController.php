<?php

namespace App\Controllers\Api;

use App\Models\Api\Saude\Simulacao\SimulacaoEntity;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use Modules\Data;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerSalvarInterface;

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
        $propriedadesEntity = pegarPropriedadeDaEntity($simulacaoEntity, lista: [
            'titular', 'quantidade_dependentes', 'operadora',
            'acomodacao', 'regiao', 'valor_titular', 'dependentes',
            'valor_total', 'plano', 'status'
        ]);

        $dependentes = jsonDecode($propriedadesEntity['dependentes'], true, true);
        foreach ($dependentes as $key => $value) {
            $dependentes[$key]['data_nascimento'] = (new Data($value['data_nascimento']))->data();
        }

        $propriedadesEntity['titular'] = (new Data($propriedadesEntity['titular']))->data();
        $propriedadesEntity['dependentes'] = $dependentes;

        return mensagemSucesso($propriedadesEntity, $status);
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
