<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Classes\DemandaTarefa\Tipo;
use App\Models\Api\Demanda\TarefaModel;
use App\Models\Api\Demanda\TarefaEntity;
use App\Models\Api\Demanda\DemandaEntity;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerAtualizarInterface;

final class DemandaTarefaController extends Controller implements
    ControllerSalvarInterface,
    ControllerBuscarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface,
    ControllerListarInterface
{
    public function getListar(Request $request): Response
    {
        $Demanda = new DemandaEntity();
        $Demanda->uuid($request->demanda);
        $Tarefa = new TarefaModel($Demanda);
        return mensagemSucesso($Tarefa->pegarListaTarefa());
    }

    public function postSalvar(Request $request): Response
    {
        $Tarefa = new TarefaEntity(
            demanda: $request->demanda,
            titulo: $request->titulo,
            texto: $request->getPost('texto', html: false),
            tipo: new Tipo($request->tipo),
            equipe: $request->equipe,
            dificuldade: $request->dificuldade
        );
        $Tarefa->salvar();

        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $Tarefa,
                lista: [
                    'id', 'equipe', 'titulo', 'texto', 'tipo', 'data_producao_inicio',
                    'data_producao_final', 'data_criacao', 'status'
                ]
            ),
            201
        );
    }

    public function getBuscar(string $id): Response
    {
        $Tarefa = new TarefaEntity();
        $Tarefa->uuid($id);
        return mensagemSucesso(
            pegarPropriedadeDaEntity($Tarefa, lista: ['id', 'titulo', 'texto', 'tipo', 'minuto_producao_estimada'])
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $dado = $request->dado();
        if (!$request->vazio('texto')) {
            $dado['texto'] = $request->getPut('texto', html: false);
        }

        $Tarefa = new TarefaEntity();
        $Tarefa->uuid($id);
        $Tarefa->set(lista: $dado);
        $Tarefa->salvar();

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id): Response
    {
        $Tarefa = new TarefaEntity();
        $Tarefa->uuid($id);
        $Tarefa->destruir();

        return new Response(status: 204);
    }

    public function postLike(string $id)
    {
        $Tarefa = new TarefaEntity();
        $Tarefa->uuid($id);
        $Tarefa->like();

        return mensagemSucesso([], 201);
    }

    public function postDeslike(Request $request, string $id)
    {
        $request->vazio('motivo', mensagem: 'O campo motivo é obrigatório.');

        $Tarefa = new TarefaEntity();
        $Tarefa->uuid($id);
        $Tarefa->deslike($request->motivo);

        return mensagemSucesso([], 201);
    }
}
