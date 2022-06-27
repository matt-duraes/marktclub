<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Controllers\Api\Interface\ListarInterface;
use App\Controllers\Api\Interface\SalvarInterface;
use App\Models\Api\PainelHistorico\HistoricoModel;
use App\Controllers\Api\Interface\DeletarInterface;
use App\Models\Api\PainelHistorico\HistoricoEntity;
use App\Controllers\Api\Interface\AtualizarInterface;

final class PainelHistoricoController extends Controller implements
    ListarInterface,
    SalvarInterface,
    AtualizarInterface,
    DeletarInterface
{
    public function postSalvar(Request $request)
    {
        $Historico = new HistoricoEntity();
        $Historico->set(lista: $request->dado());
        $Historico->salvar();

        return mensagemSucesso(
            pegarPropriedadeDaEntity($Historico, request: $request, lista: ['id', 'relacionado', 'app', 'acao', 'dado', 'mensagem']),
            status: 201
        );
    }

    public function getListar(Request $request)
    {
        $Historico = new HistoricoModel($request);
        $dado = $Historico->listarDados();
        return mensagemSucesso($dado);
    }

    public function putAtualizar(Request $request, string $id)
    {
        $Historico = new HistoricoEntity;
        $Historico->id($id);
        $Historico->mensagem = $request->mensagem;
        $Historico->salvar();

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id)
    {
        $Historico = new HistoricoEntity;
        $Historico->id($id);
        $Historico->destruir();

        return new Response(status: 204);
    }
}
