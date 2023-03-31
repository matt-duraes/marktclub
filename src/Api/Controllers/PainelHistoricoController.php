<?php

namespace ApiController;

use ApiModel\PainelHistorico\HistoricoEntity;
use ApiModel\PainelHistorico\HistoricoModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use System\Interface\ControllerAtualizarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;

final class PainelHistoricoController extends Controller implements
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    /**
     * @param  Request  $request
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $Historico = new HistoricoEntity();
        $Historico->set(lista: $request->dado());
        $Historico->salvar();

        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $Historico,
                $request,
                ['id', 'relacionado', 'app', 'acao', 'dado', 'mensagem']
            ),
            201
        );
    }

    /**
     * @param  Request  $request
     * @return Response
     * @throws Excecao
     */
    public function getListar(Request $request): Response
    {
        $Historico = new HistoricoModel($request);
        $dado = $Historico->listarDados();
        return mensagemSucesso($dado);
    }

    /**
     * @param  Request  $request
     * @param  string   $id
     * @return Response
     * @throws Excecao
     */
    public function putAtualizar(Request $request, string $id): Response
    {
        $Historico = new HistoricoEntity();
        $Historico->uuid($id);
        $Historico->mensagem = $request->mensagem;
        $Historico->salvar();

        return new Response(status: 204);
    }

    /**
     * @param  string  $id
     * @return Response
     * @throws Excecao
     */
    public function deleteDeletar(string $id): Response
    {
        $Historico = new HistoricoEntity();
        $Historico->uuid($id);
        $Historico->destruir();

        return new Response(status: 204);
    }
}
