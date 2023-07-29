<?php

namespace App\Controllers\Site;

use Http\Request;
use Http\Response;
use Helpers\AuthHelper;
use Helpers\ListaHelper;
use Controller\Controller;
use App\Models\Site\Login\LogarModel;
use App\Models\Site\Contato\SalvarModel as SalvarContatoModel;

final class LoginController extends Controller
{
    public function index(): Response
    {
        return view('login.index');
    }

    public function postLogar(Request $request): Response
    {
        new LogarModel($request->login, $request->senha);
        return mensagemSucesso([
            'link' => (new AuthHelper())->location()
        ], status: 201);
    }

    public function comoFunciona(): Response
    {
        return view('como_funciona.index');
    }

    public function comoFuncionaCFM(): Response
    {
        return view('como_funciona_cfm.index');
    }

    public function comoFuncionaDependente(): Response
    {
        return view('como_funciona_dependente.index');
    }

    public function comoFuncionaFuncionario(): Response
    {
        return view('como_funciona_funcionario.index');
    }

    public function faq(): Response
    {
        return view('faq.index');
    }

    public function abrirModalContato()
    {
        return view('login.index.modalContato');
    }

    public function postContato(Request $request): Response
    {
        $contato = new SalvarContatoModel($request);
        $contato = $contato->postSalvar();

        return new Response(json: [
            'status' => 'sucesso'
        ], status: 201);
    }

    public function ativar()
    {
        return view('ativar_cadastro.index', [
            'ativacao' => 'siape',
            'id_admin_empresa' => 1,
            'dependente' => false,
            'matricula' => false,
            'genero'    => (new ListaHelper())->add('', 'Escolha uma opção')->genero()->r(),
            'ddi'    => (new ListaHelper())->add('', 'Escolha uma opção')->ddi()->r(),
            'uf' => (new ListaHelper())->add('UF')->uf()->r(),
            'client_id' => ''
        ]);
    }

    public function postBuscarUsuario(Request $request): Response
    {
        return new Response(json: [
            'status' => 'sucesso'
        ], status: 201);
    }

    public function postAtivar(Request $request): Response
    {
        return new Response(json: [
            'status' => 'sucesso',
            'empresa' => 1,
            'limite' => 1,
            'liberacao_dependente' => 1,
            'tipo' => 1
        ], status: 201);
    }

    public function logarUsuario()
    {
        return view('logar.index', [
            'client_id' => '',
            'tipo' => '',
            'indicacao' => false,
            'link_cadastro' => 'https://markt.club',
            'dependente' => false
        ]);
    }

    public function postEnviarCodigo(Request $request): Response
    {
        return new Response(json: [
            'status' => 'sucesso'
        ], status: 201);
    }

    public function postValidarCodigo(Request $request): Response
    {
        return new Response(json: [
            'status' => 'sucesso'
        ], status: 201);
    }

    public function postNovaSenha(Request $request): Response
    {
        return new Response(json: [
            'status' => 'sucesso'
        ], status: 201);
    }

    public function sair(): Response
    {
        sessaoDestruir();
        cookieDeletar('CLT');
        return new Response(url: LINK . '/login');
    }
}
