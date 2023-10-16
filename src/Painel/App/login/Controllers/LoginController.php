<?php

namespace PainelApp\login\Controllers;

use Http\Request;
use Http\Response;
use Helpers\AuthHelper;
use Controller\Controller;
use PainelApp\login\Models\LoginFormModel;
use PainelApp\login\Models\LoginInterface;
use PainelApp\login\Models\LoginSocialModel;
use PainelApp\login\Models\LoginAutorizadoModel;

final class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $location = base64Decode($request->chave('location', ''));
        if (empty($location) || !str_starts_with($location, LINK) || preg_match('/\/login/', $location)) {
            $location = LINK;
        }
        return view(arquivo: 'login.index', var: [
            'location' => $location
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | LOGAR COM FORMULÁRIO
    |--------------------------------------------------------------------------
    */
    public function postLogin(Request $request): Response
    {
        $request
            ->vazio('login', mensagem: 'O campo login é obrigatório.')
            ->vazio('senha', mensagem: 'O campo senha é obrigatório.');

        $Login = new LoginFormModel(
            login: $request->login,
            senha: $request->senha
        );

        return $this->loginRealizado($Login);
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN SOCIAL
    |--------------------------------------------------------------------------
    */
    public function postSocial(Request $request)
    {
        $mensagemErro = 'Ocorre um erro ao fazer login, por favor, tente novamente.';
        $request
            ->vazio('rede', $mensagemErro)
            ->vazio('id', $mensagemErro)
            ->vazio('token', $mensagemErro)
            ->vazio('code', $mensagemErro);

        $Login = new LoginSocialModel(
            rede: $request->rede,
            id: $request->id,
            accessToken: $request->token,
            code: $request->code
        );

        return $this->loginRealizado($Login);
    }

    private function loginRealizado(LoginInterface $Login): Response
    {
        new LoginAutorizadoModel(Login: $Login);
        return mensagemSucesso(['id' => uuid()], 201);
    }

    /*
    |--------------------------------------------------------------------------
    | SAIR
    |--------------------------------------------------------------------------
    */
    public function sair(): Response
    {
        (new AuthHelper())->deletar();
        cookieDeletar('FWT');
        return new Response(url: route('login.index'));
    }

    public function bloquear(): Response
    {
        (new AuthHelper())->deletar();
        cookieDeletar('FWT');
        return new Response(status: 200);
    }
}
