<?php

namespace App\Controllers\Site;

use Http\Request;
use Http\Response;
use Helpers\AuthHelper;
use Controller\Controller;
use App\Models\Site\Login\LogarModel;
use App\Models\Site\Contato\SalvarModel as SalvarContatoModel;

final class LoginController extends Controller
{
    public function index(): Response
    {
        return view('login.index');
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */
    public function login()
    {
        $api = sessao('CLUBE')->api;
        $linkLogin = sessao('CLUBE')->link_login;
        return view('login.login', [
            'api'        => $api,
            'link_login' => $linkLogin
        ]);
    }

    public function postLogin(Request $request): Response
    {
        new LogarModel($request->login, $request->senha);
        $link = (new AuthHelper())->location();
        return mensagemSucesso([
            'link' => str_contains($link, '/login') ? LINK : $link
        ], status: 201);
    }

    /*
    |--------------------------------------------------------------------------
    | ATIVAR
    |--------------------------------------------------------------------------
    */
    public function buscarConta()
    {
        return view('login.buscar');
    }

    public function postBuscarConta(Request $request): Response
    {
        return new Response(json: [
            'status' => 'sucesso'
        ], status: 201);
    }

    public function ativar(): Response
    {
        return view('login.ativar');
    }

    public function postAtivar(Request $request): Response
    {
        return new Response(json: [], status: 201);
    }

    public function faq(): Response
    {
        return view('faq.index');
    }

    /*
    |--------------------------------------------------------------------------
    | CONTATO
    |--------------------------------------------------------------------------
    */
    public function contato()
    {
        return view('login.contato');
    }

    public function postContato(Request $request): Response
    {
        $contato = new SalvarContatoModel($request);
        $contato = $contato->postSalvar();

        return new Response(json: [
            'status' => 'sucesso'
        ], status: 201);
    }
}
