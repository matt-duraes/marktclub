<?php

namespace PainelApp\agenda\Controllers;

use Http\Request;
use Http\Response;
use Helpers\ApiHelper;
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
        return view('painel.agenda.index', [
            'appTitulo' => '',
            'app' => 'agenda',
            'agenda' => true,
            'logado' => (new SocialHelper(rede: 'google'))->logado([
                'https://www.googleapis.com/auth/calendar.events',
                'https://www.googleapis.com/auth/calendar.readonly'
            ])
        ]);
    }

    public function postLogin(Request $request)
    {
        $Social = new SocialHelper(rede: 'google', code: $request->code);
        $this->atualizarIdGoogle($Social->id());
        return mensagemSucesso(['logado' => true], status: 201);
    }
    private function atualizarIdGoogle($id)
    {
        $idUsuario = sessao('USUARIO.google');
        if (!empty($idUsuario) && $id == $idUsuario) {
            return;
        }

        $Api = new ApiHelper(token: true);
        $chave = $Api->get('/admin/chave-publica')->object()->dado->chave ?? '';
        $status = $Api->body([
            'id_google' => criptografarDado($id, chave: $chave)
        ])->put('/usuario-equipe/' . sessao('USUARIO.id'))->status();

        if ($status != 204) {
            mensagemErro('Erro!', 'Ocorreu um erro ao vincular sua conta, por favor, tente novamente.');
        }
        sessao('USUARIO.google', $id);
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
