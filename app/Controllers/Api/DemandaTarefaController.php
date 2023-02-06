<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Classes\DemandaTarefa\Tipo;
use App\Models\Api\Demanda\TarefaEntity;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerAtualizarInterface;

final class DemandaTarefaController extends Controller implements
    ControllerSalvarInterface,
    ControllerBuscarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    public function postSalvar(Request $request)
    {
        $minuto = $request->vazio('minuto_producao_estimada') ? null : $request->minuto_producao_estimada;
        $Tarefa = new TarefaEntity(
            demanda: $request->demanda,
            titulo: $request->titulo,
            texto: $request->_POST('texto', html: false),
            tipo: new Tipo($request->tipo),
            minuto_producao_estimada: $minuto,
            equipe: $request->equipe
        );
        $Tarefa->salvar();

        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $Tarefa,
                lista: [
                    'id', 'titulo', 'texto', 'tipo', 'data_criacao', 'minuto_producao_estimada', 'status'
                ]
            ),
            201
        );
    }

    public function getBuscar(string $id)
    {
        $Tarefa = new TarefaEntity();
        $Tarefa->id($id);
        return mensagemSucesso(
            pegarPropriedadeDaEntity($Tarefa, lista: ['id', 'titulo', 'texto', 'tipo', 'minuto_producao_estimada'])
        );
    }

    public function putAtualizar(Request $request, string $id)
    {
        $dado = $request->dado();
        if (!$request->vazio('texto')) {
            $dado['texto'] = $request->_PUT('texto', html: false);
        }

        $Tarefa = new TarefaEntity();
        $Tarefa->id($id);
        $Tarefa->set(lista: $dado);
        $Tarefa->salvar();

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id)
    {
        $Tarefa = new TarefaEntity();
        $Tarefa->id($id);
        $Tarefa->destruir();

        return new Response(status: 204);
    }

    public function postLike(string $id)
    {
        $Tarefa = new TarefaEntity();
        $Tarefa->id($id);
        $Tarefa->like();

        return mensagemSucesso([], 201);
    }
    public function postDeslike(Request $request, string $id)
    {
        $Tarefa = new TarefaEntity();
        $Tarefa->id($id);
        $Tarefa->deslike($request->motivo);

        return mensagemSucesso([], 201);
    }
}
