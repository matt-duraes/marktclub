<?php

namespace App\Controllers\Site;

use Http\Request;
use Http\Response;
use Helpers\AuthHelper;
use Controller\Controller;
use App\Models\Site\Login\LogarModel;

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

    public function sair(): Response
    {
        sessaoDestruir();
        cookieDeletar('CLT');
        return new Response(url: LINK . '/login');
    }
}
