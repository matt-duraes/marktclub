<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Classes\DemandaTarefa\Tipo;
use App\Models\Api\Demanda\TarefaEntity;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerAtualizarInterface;

final class DemandaTarefaController extends Controller implements
    ControllerSalvarInterface,
    ControllerBuscarInterface,
    ControllerAtualizarInterface
{
    public function postSalvar(Request $request)
    {
        $Tarefa = new TarefaEntity(
            demanda: $request->demanda,
            titulo: $request->titulo,
            texto: $request->_POST('texto', html: false),
            tipo: new Tipo($request->tipo),
            hora_producao_estimada: $request->hora_producao_estimada,
            equipe: $request->equipe
        );
        $Tarefa->salvar();

        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $Tarefa,
                lista: [
                    'id', 'titulo', 'texto', 'tipo', 'data_criacao', 'hora_producao_estimada', 'status'
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
            pegarPropriedadeDaEntity($Tarefa, lista: ['id', 'titulo', 'texto', 'tipo'])
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
}
