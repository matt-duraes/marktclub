<?php

namespace ApiController;

use Erro\Excecao;
use Http\Request;
use Http\Response;
use Controller\Controller;
use ApiModel\PainelHistorico\DownloadModel;
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
    /**
     * @param  Request  $request
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $Historico = new HistoricoEntity();
        $Historico->set(
            lista: $request->lista([
                'relacionado', 'app', 'acao', 'mensagem', 'notificar_titulo', 'notificar_link', 'notificar_equipe'
            ])
        );
        foreach ($request->getFiles() as $arquivo) {
            $Historico->arquivo($arquivo);
        }
        $Historico->salvar();

        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $Historico,
                $request,
                ['id', 'relacionado', 'app', 'acao', 'dado', 'mensagem', 'arquivo']
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
        $Historico = new HistoricoModel();
        $Historico->set(lista: $request->dado());

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
     * @param  string   $id
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

    public function postDownload(Request $request): Response
    {
        $Download = new DownloadModel($request);
        return mensagemSucesso([
            'id' => $Download->id
        ], 201);
    }
}
