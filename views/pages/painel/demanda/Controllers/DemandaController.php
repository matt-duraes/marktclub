<?php

namespace Painel\Demanda\Controllers;

use Http\Request;
use Http\Response;
use Modules\Botao;
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

        $empresa = $Api
            ->json(['titulo' => 'Escolha um cliente'])
            ->get('/admin-empresa/select')
            ->array();
        $equipe = $Api
            ->json(['titulo' => 'Escolha um usuário'])
            ->get('/usuario-equipe/select')
            ->array();

        return view('painel.demanda.demanda-editar', [
            'r' => $demanda->dado,
            'empresa' => $empresa['dado'] ?? [],
            'equipe' => $equipe['dado'] ?? [],
            'dataEntregaClasse' => $demanda->dado->com_prazo == 'sim' ? 'ativo' : ''
        ]);
    }

    public function postDemandaEditar(Request $request, string $id)
    {
        $request
            ->vazio('titulo', mensagem: 'Digite um título para continuar.')
            ->vazio('empresa', mensagem: 'Escolha uma empresa para continuar.')
            ->vazio('dono', mensagem: 'Escolha um dono da demanda para continuar.')
            ->validarData('data_entrega', mensagem: 'Digite uma data de entrega válida para continuar.');

        $Api = new ApiHelper(token: true);
        $Api
            ->validar('Ocorre um erro ao editar sua demanda, por favor, tente novamente.')
            ->body([
                'titulo' => $request->titulo,
                'id_admin_empresa' => $request->empresa,
                'id_usuario_equipe' => $request->dono,
                'com_prazo' => $request->com_prazo,
                'data_entrega' => !empty($request->data_entrega) ? dataBanco($request->data_entrega) : '',
            ])
            ->put('/demanda-dado/' . $id);

        return new Response(status: 204);
    }

    public function tarefaSalvar(string $demanda)
    {
        $Tipo = new DemandaTarefaTipo();
        return view('painel.demanda.tarefa-salvar', [
            'tipoLista' => $Tipo->select('Escolha uma opção'),
            'demanda' => $demanda
        ]);
    }
    public function postTarefaSalvar(Request $request)
    {
        $request
            ->vazio('demanda', mensagem: 'Você deve passar a demanda da tarefa.')
            ->vazio('titulo', mensagem: 'Digite o título da tarefa para continuar.')
            ->vazio('texto', mensagem: 'Digite o texto da tarefa para continuar.')
            ->vazio('tipo', mensagem: 'Escolha um tipo para a tarefa.');

        $Api = new ApiHelper(token: true);
        $tarefa = $Api
            ->validar('Erro ao salvar nova tarefa, por favor, tente novamente.')
            ->body([
                'demanda' => $request->demanda,
                'titulo' => $request->titulo,
                'texto' => $request->_POST('texto', html: false),
                'tipo' => $request->tipo,
                'hora_producao_estimada' => $request->hora,
            ])
            ->post('/demanda-tarefa')->object();

        return mensagemSucesso($tarefa, 201);
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
                new Botao($request->login_api),
                $request->login_link,
                new Botao($request->app),
                $request->_POST('texto', html: false),
                new Botao($request->cdn)
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
        $Api = new ApiHelper(token: true);
        $Api
            ->validar('Erro ao deletar a tarefa, por favor, tente novamente.')
            ->delete('/demanda-tarefa/' . $id);

        return new Response(status: 204);
    }
}
