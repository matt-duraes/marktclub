<?php

namespace ApiController;

use Http\Request;
use Http\Response;
use Controller\Controller;
use ApiModel\PainelHistorico\HistoricoModel;
use ApiModel\PainelHistorico\HistoricoEntity;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerAtualizarInterface;

final class PainelHistoricoController extends Controller implements
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    public function postSalvar(Request $request): Response
    {
        $Historico = new HistoricoEntity();
        $Historico->set(lista: $request->dado());
        $Historico->salvar();

        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $Historico,
                request: $request,
                lista: ['id', 'relacionado', 'app', 'acao', 'dado', 'mensagem']
            ),
            status: 201
        );
    }

    public function getListar(Request $request): Response
    {
        $Historico = new HistoricoModel($request);
        $dado = $Historico->listarDados();
        return mensagemSucesso($dado);
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $Historico = new HistoricoEntity();
        $Historico->uuid($id);
        $Historico->mensagem = $request->mensagem;
        $Historico->salvar();

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id): Response
    {
        $Historico = new HistoricoEntity();
        $Historico->uuid($id);
        $Historico->destruir();

        return new Response(status: 204);
    }
}
