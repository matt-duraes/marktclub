<?php

namespace PainelApp\agenda\Controllers;

use Http\Request;
use Http\Response;
use Helpers\SocialHelper;
use Controller\Controller;
use PainelApp\agenda\Models\BuscarModel;
use PainelApp\agenda\Models\EditarModel;
use PainelApp\agenda\Models\SalvarModel;
use PainelApp\agenda\Models\DeletarModel;
use PainelApp\agenda\Models\RespostaModel;

final class AgendaController extends Controller
{
    public function index()
    {
        $Social = new SocialHelper(
            rede: 'google'
        );

        return view('painel.agenda.index', [
            'appTitulo' => '',
            'app' => 'agenda',
            'agenda' => true,
            'logado' => $Social->logado()
        ]);
    }

    public function postLogin(Request $request)
    {
        new SocialHelper(rede: 'google', code: $request->code);
        return mensagemSucesso(['logado' => true], status: 201);
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
        $Social = new SocialHelper(rede: 'google');
        if ($Social->logado()) {
            return $Social->token();
        }
        return '';
    }
}
