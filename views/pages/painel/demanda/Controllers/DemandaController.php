<?php

namespace Painel\Demanda\Controllers;

use Http\Request;
use Helpers\ApiHelper;
use Controller\Controller;
use Painel\Demanda\Models\CriarClienteModel;

final class DemandaController extends Controller
{
    public function lista()
    {
        return view('painel.demanda.index', [
            'nova' => $this->buscarDemanda('nova', 'mais-novo'),
            'liberada' => $this->buscarDemanda('liberada', 'ordem'),
            'andamento' => $this->buscarDemanda('andamento', 'mais-novo'),
            'finalizada' => $this->buscarDemanda('finalizada', 'mais-novo'),
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

    public function detalhe(string $id)
    {
        return view('painel.demanda.detalhe');
    }
}
