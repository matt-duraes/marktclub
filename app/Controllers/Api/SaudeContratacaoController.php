<?php

namespace App\Controllers\Api;

use App\Models\Api\Saude\Contratacao\ContratacaoEntity;
use App\Models\Api\Saude\Simulacao\SimulacaoEntity;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use System\Interface\ControllerSalvarInterface;

class SaudeContratacaoController extends Controller implements
    ControllerSalvarInterface
{
    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $SimulacaoEntity = new SimulacaoEntity();
        $SimulacaoEntity->uuid($request->getPost('id_simulacao'));

        $ContratacaoEntity = new ContratacaoEntity($SimulacaoEntity);
        $ContratacaoEntity->set(lista: $request->dado());
        $ContratacaoEntity->salvar();
        return mensagemSucesso([], 201);
    }
}
