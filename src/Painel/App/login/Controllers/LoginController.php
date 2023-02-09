<?php

namespace PainelApp\login\Controllers;

use Http\Request;
use Http\Response;
use Helpers\ApiHelper;
use Helpers\AuthHelper;
use Helpers\SocialHelper;
use Controller\Controller;
use PainelApp\login\Models\PainelModel;
use PainelApp\login\Models\LoginFormModel;
use PainelApp\login\Models\LoginInterface;
use PainelApp\login\Models\LoginSocialModel;
use PainelApp\login\Models\BuscarUsuarioModel;
use PainelApp\login\Models\AutenticarUsuarioModel;
use PainelApp\login\Models\MontarPermissoesPainelModel;

final class LoginController extends Controller
{
    private string $chavePublica;
    private string $chavePrivada;

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        return view(arquivo: 'login.index');
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
        new AutenticarUsuarioModel(
            Login: $Login
        );
        new BuscarUsuarioModel();
        new PainelModel();

        return mensagemSucesso([
            'link' => (new AuthHelper)->location()
        ], 201);
    }

    /*
    |--------------------------------------------------------------------------
    | SAIR
    |--------------------------------------------------------------------------
    */
    public function sair(): Response
    {
        (new AuthHelper)->deletar();
        sessaoDeletar('relatorio');
        sessaoDeletar('TOKEN');
        return new Response(url: route('login.index'));
    }

    public function setarChave()
    {
        $Api = new ApiHelper('admin:chave_publica admin:chave_privada');
        $this->chavePublica = $Api->get('/admin/chave-publica')->object()->dado->chave ?? '';
        $this->chavePrivada = $Api->get('/admin/chave-privada')->object()->dado->chave ?? '';
    }
}
