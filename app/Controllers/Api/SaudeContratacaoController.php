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
        $SaudeContratacao = new ContratacaoEntity(new SimulacaoEntity());
        $SaudeContratacao->set(lista: $request->dado());
        $SaudeContratacao->salvar();
        return mensagemSucesso($SaudeContratacao->retorno(), 201);
    }
}
