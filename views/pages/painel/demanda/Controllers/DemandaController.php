<?php

namespace Painel\Demanda\Controllers;

use Http\Request;
use Helpers\ApiHelper;
use Controller\Controller;
use App\Classes\DemandaDado\Tipo;
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
    private function buscarDemanda($status, $ordem)
    {
        $Api = new ApiHelper(token: true);
        $lista = $Api->json([
            'status' => $status,
            'ordem' => $ordem
        ])->get('/demanda-dado')->object();
        return $lista->dado ?? [];
    }

    public function add()
    {
        $Api = new ApiHelper(token: true);
        $empresa = $Api
            ->json(['titulo' => 'Escolha um cliente'])
            ->get('/admin-empresa/select')
            ->array();

        return view('painel.demanda.nova', [
            'empresa' => $empresa['dado'] ?? []
        ]);
    }
    public function postAdd(Request $request)
    {
        if ($request->tipo == 'cliente') {
            $Demanda = new CriarClienteModel(
                $request->empresa,
                $request->dominio_tipo,
                $request->dominio_link,
                $request->login_api,
                $request->login_link,
                $request->app,
                $request->texto
            );
        }

        return mensagemSucesso(['id' => $Demanda->id()], 201);
    }

    public function tarefa(string $id)
    {
        $Api = new ApiHelper(token: true);
        $tarefa = $Api->get('/demanda-dado/' . $id)->object();

        if (!object_key_exists('dado', $tarefa)) {
            mensagemStatus(404);
        }

        return view('painel.demanda.tarefa');
    }
}
