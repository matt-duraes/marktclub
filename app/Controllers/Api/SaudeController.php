<?php

namespace App\Controllers\Api;

use App\Models\Api\Saude\ContratacaoEntity;
use App\Models\Api\Saude\SimulacaoEntity;
use App\Models\Api\Saude\SimulacaoModel;
use Controller\ControllerInterface;
use Erro\Excecao;
use Http\Request;
use Http\Response;

class SaudeController implements ControllerInterface
{
    /**
     * @param  Request  $request
     * @param  string   $id
     *
     * @return Response
     * @throws Excecao
     */
    public function getBuscarSimulacao(Request $request, string $id): Response
    {
        $SaudeSimulacao = new SimulacaoModel($request);
        $simulacao = $SaudeSimulacao->validarSimulacao($id);

        if ($simulacao === null) {
            mensagemErro('Não conseguimos concluir a busca', 'Simulação não encontrada ou inválida');
        }

        return mensagemSucesso($simulacao);
    }

    /**
     * @param  Request  $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSimularPlano(Request $request): Response
    {
        $SaudeSimulacao = new SimulacaoEntity($request);
        $SaudeSimulacao->salvar();
        return mensagemSucesso($SaudeSimulacao->retorno(), 201);
    }

    /**
     * @param  Request  $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postContratarPlano(Request $request): Response
    {
        $SaudeContratacao = new ContratacaoEntity($request);
        $SaudeContratacao->salvar();
        return mensagemSucesso($SaudeContratacao->retorno(), 201);
    }
}
