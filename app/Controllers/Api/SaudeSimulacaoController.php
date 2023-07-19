<?php

namespace App\Controllers\Api;

use App\Models\Api\Saude\Simulacao\SimulacaoEntity;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use ORM\Entity;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerSalvarInterface;

class SaudeSimulacaoController extends Controller implements
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
     * @param Entity $entity
     * @param int    $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoPadrao(Entity $entity, int $status = 200): Response
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity($entity, lista: [
                'data_nascimento', 'quantidade_dependentes', 'operadora',
                'acomodacao', 'regiao', 'valor_titular', 'valor_dependentes',
                'valor_total', 'plano', 'status'
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
    public function postSalvar(Request $request): Response
    {
        $SimulacaoEntity = new SimulacaoEntity($request);
        $SimulacaoEntity->salvar();
        return $this->retornoPadrao($SimulacaoEntity, 201);
    }
}
