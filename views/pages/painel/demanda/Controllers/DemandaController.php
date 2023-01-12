<?php

namespace Painel\Demanda\Controllers;

use Http\Request;
use Http\Response;
use Helpers\ApiHelper;
use Controller\Controller;
use App\Classes\DemandaDado\Tipo;
use App\Classes\DemandaTarefa\Status;
use Painel\Demanda\Models\CriarClienteModel;
use App\Classes\DemandaTarefa\Tipo as DemandaTarefaTipo;

final class DemandaController extends Controller
{
    public function lista()
    {
        return view('painel.demanda.index', [
            'nova' => $this->buscarDemanda('nova', 'mais-novo'),
            'liberada' => $this->buscarDemanda('liberada', 'ordem'),
            'andamento' => $this->buscarDemanda('andamento', 'mais-novo'),
            'finalizada' => $this->buscarDemanda('finalizada', 'mais-novo'),
            'Tipo' => new Tipo(),
            'Area' => new DemandaTarefaTipo()
        ]);
    }

    public function demanda(string $id)
    {
        $Api = new ApiHelper(token: true);
        $tarefa = $Api->get('/demanda-dado/' . $id)->object();

        if (!object_key_exists('dado', $tarefa)) {
            mensagemStatus(404);
        }

        return view('painel.demanda.demanda', [
            'r' => $tarefa->dado,
            'Tipo' => new DemandaTarefaTipo(),
            'Status' => new Status()
        ]);
    }

    public function demandaSalvar()
    {
        $Api = new ApiHelper(token: true);
        $empresa = $Api
            ->json(['titulo' => 'Escolha um cliente'])
            ->get('/admin-empresa/select')
            ->array();
        return view('painel.demanda.demanda-salvar', [
            'empresa' => $empresa['dado'] ?? []
        ]);
    }

    public function demandaEditar(string $id)
    {
        $Api = new ApiHelper(token: true);
        $demanda = $Api->get('/demanda-dado/' . $id)->object();

        return view('painel.demanda.tarefa-editar', [
            'r' => $demanda->dado
        ]);
    }

    public function tarefaEditar(string $id, string $demanda)
    {
        $Api = new ApiHelper(token: true);
        $tarefa = $Api->get('/demanda-tarefa/' . $id)->object();

        if (!object_key_exists('dado', $tarefa)) {
            mensagemStatus(404);
        }

        $Tipo = new DemandaTarefaTipo();

        return view('painel.demanda.tarefa-editar', [
            'demanda' => $demanda,
            'tipoLista' => $Tipo->select('Escolha uma opção'),
            'r' => $tarefa->dado
        ]);
    }

    private function buscarDemanda($status, $ordem)
    {
        $Api = new ApiHelper(token: true);
        $lista = $Api->json([
            'status' => $status,
            'ordem' => $ordem
        ])->get('/demanda-dado')->object();
        return $lista->dado ?? [];
    }

    public function postDemandaSalvar(Request $request)
    {
        if ($request->tipo == 'cliente') {
            $Demanda = new CriarClienteModel(
                $request->empresa,
                $request->dominio_tipo,
                $request->dominio_link,
                $request->login_api,
                $request->login_link,
                $request->app,
                $request->_POST('texto', html: false)
            );
        }

        return mensagemSucesso(['id' => $Demanda->id()], 201);
    }

    public function postTarefaEditar(Request $request, string $id)
    {
        $request
            ->vazio('titulo', mensagem: 'Você precisa passar um título para a tarefa.')
            ->vazio('texto', mensagem: 'Você precisa passar um texto para a tarefa.')
            ->vazio('tipo', mensagem: 'Você precisa passar um tipo para a tarefa.');

        $Api = new ApiHelper(token: true);
        $dado = $Api->body([
            'titulo' => $request->titulo,
            'texto' => $request->_POST('texto', html: false),
            'tipo' => $request->tipo,
            'hora_producao_estimada' => $request->hora
        ])->put('/demanda-tarefa/' . $id);

        respostaJson(
            resposta: $dado,
            mensagem: 'Ocorre um erro ao atualizar sua demanda, por favor, tente novamente.'
        );

        return new Response(status: 204);
    }
    public function postTarefaArquivo(Request $request, string $id)
    {
        $Api = new ApiHelper(token: true);
        $Api
            ->validar('Ocorre um erro ao atualizar lista de arquivos, por favor, tente novamente.')
            ->body([
                'arquivo' => jsonEncode($request->arquivo)
            ])
            ->put('/demanda-dado/' . $id);

        return new Response(status: 204);
    }

    public function deleteTarefa(string $id)
    {
        return new Response(status: 204);
    }
}
