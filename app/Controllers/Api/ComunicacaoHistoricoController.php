<?php

namespace App\Controllers\Api;

use Http\Request;
use Modules\Data;
use Http\Response;
use Modules\Pagina;
use Modules\Quantidade;
use Controller\Controller;
use App\Classes\Geral\Status;
use App\Classes\ComunicacaoHistorico\Ordem;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerAtualizarInterface;
use App\Models\Api\ComunicacaoHistorico\HistoricoModel;
use App\Models\Api\ComunicacaoHistorico\HistoricoEntity;

final class ComunicacaoHistoricoController extends Controller implements
    ControllerListarInterface,
    ControllerBuscarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    public function getListar(Request $request): Response
    {
        $Historico = new HistoricoModel(
            pagina: new Pagina($request->pagina),
            quantidade: new Quantidade($request->quantidade),
            titulo: $request->titulo,
            dataInicio: new Data($request->data_inicio),
            dataFinal: new Data($request->data_final),
            status: new Status($request->status),
            ordem: new Ordem($request->ordem)
        );
        return mensagemSucesso(
            $Historico->listarDados()
        );
    }

    public function getBuscar(string $id): Response
    {
        $Historico = new HistoricoEntity();
        $Historico->uuid($id);

        return $this->retornoPadrao($Historico);
    }

    public function postSalvar(Request $request): Response
    {
        $Historico = new HistoricoEntity();
        $Historico->set(lista: $request->dado());
        $Historico->salvar();

        return $this->retornoPadrao($Historico);
    }

    private function retornoPadrao(HistoricoEntity $Historico, int $status = 200)
    {
        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity(
                $Historico,
                lista: [
                    'id', 'empresa', 'parceiro', 'titulo', 'data_inicio', 'data_final', 'data_criacao', 'status'
                ]
            ),
            status: $status
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $Historico = new HistoricoEntity();
        $Historico->uuid($id);
        $Historico->set(lista: $request->dado());
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
