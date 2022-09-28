<?php

namespace PainelApp\agenda\Controllers;

use Http\Request;
use Http\Response;
use Controller\Controller;
use Painel\Agenda\Models\BuscarModel;
use Painel\Agenda\Models\EditarModel;
use Painel\Agenda\Models\SalvarModel;
use Painel\Agenda\Models\DeletarModel;
use Painel\Agenda\Models\RespostaModel;

final class AgendaController extends Controller
{
    public function index()
    {
        return view('painel.agenda.index', [
            'appTitulo' => '',
            'app' => 'agenda',
            'agenda' => true
        ]);
    }

    public function postBuscar(Request $request)
    {
        $Buscar = new BuscarModel($this->token());
        $eventos = $Buscar->buscarListaEvento(
            $request->data_inicial,
            $request->data_final,
        );

        return new Response(json: ['lista' => $eventos], status: 201);
    }

    public function postSalvar(Request $request)
    {
        $Salvar = new SalvarModel($request, $this->token());
        $evento = $Salvar->salvarEvento();

        return new Response(status: 201, json: ['evento' => $evento]);
    }

    public function postEditar(Request $request)
    {
        $Editar = new EditarModel($request, $this->token());
        $evento = $Editar->editarEvento();

        return new Response(status: 201, json: ['evento' => $evento]);
    }

    public function postConfirmar(Request $request)
    {
        $Resposta = new RespostaModel($request, $this->token());
        $evento = $Resposta->confirmarPresenca();

        return new Response(status: 201, json: ['evento' => $evento]);
    }

    public function postDeletar(Request $request)
    {
        $Deletar = new DeletarModel($this->token());
        $Deletar->deletarEvento($request->id);

        return new Response(status: 204);
    }

    private function token()
    {
        $token = getallheaders()['Authorization'] ?? getallheaders()['authorization'] ?? '';
        if (empty($token)) {
            mensagemStatus(403);
        }
        return $token;
    }
}
