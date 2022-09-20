<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Api\LoginPainel\LoginFormModel;
use App\Models\Api\LoginPainel\LoginGoogleModel;
use App\Models\Api\LoginPainel\LoginFacebookModel;
use App\Models\Api\ApiToken\TokenAuthorizationEntity;
use App\Models\Api\LoginApi\LoginModel as LoginApiModel;
use App\Models\Api\LoginClube\LoginModel as LoginClubeModel;

final class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LOGIN CLUBE
    |--------------------------------------------------------------------------
    */
    public function postLoginApi(Request $request)
    {
        $Login = new LoginApiModel($request);
        return mensagemSucesso([
            'link' => $Login->link()
        ], status: 201);
    }
    public function loginApiOk($hash)
    {
        $dado = base64Decode($hash);
        if (
            !is_array($dado) ||
            !array_key_exists('nome', $dado) ||
            sessaoExiste('LOGIN_API_' . $dado['hash']) ||
            $dado['data'] < dataRemover(date('Y-m-d H:i:s'), 2, 'minutos', 'Y-m-d H:i:s')
        ) {
            mensagemStatus(404);
        }

        sessao('LOGIN_API_' . $dado['hash'], true);
        return view('login.homologacao', ['nome' => $dado['nome']]);
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN PAINEL
    |--------------------------------------------------------------------------
    */
    public function postLoginPainel(Request $request)
    {
        $dado = (object)$request->dado();

        if (!empty($dado->facebook)) {
            $Login = new LoginFacebookModel($dado->facebook);
        } else if (!empty($dado->google)) {
            $Login = new LoginGoogleModel($dado->google);
        } else {
            $Login = new LoginFormModel($dado->login, $dado->senha);
        }

        $Usuario = $Login->pegarUsuario();
        $dado = criptografarDado([
            'sub' => $Usuario->id,
            'company_id' => base64Encode($Usuario->id_admin_empresa),
            'name' => $Usuario->nome->nome(),
            'picture' => $Usuario->imagem,
            'create_at' => $Usuario->data_criacao->date(),
            'updated_at' => $Usuario->data_atualizacao->date(),
            'document' => $Usuario->cpf->cpf(),
            'email' => $Usuario->email->email(),
            'email_verified' => false,
            'new_access' => $Usuario->primeiro_acesso->valor(),
            'permission' => $Usuario->permissao,
        ], lista: ['name', 'picture', 'document', 'email']);

        return $this->criarToken($dado, $request);
    }

    private function criarToken(array $body, Request $request): Response
    {
        $Token = new TokenAuthorizationEntity();
        $token = $Token->criarToken(
            TOKEN['app'],
            $body,
            empty($request->scope) ? [] : explode(' ', $request->scope),
            $request->audience,
            $request->redirect_uri,
            $request->state,
            'sim'
        );

        return new Response(json: [
            'status' => 'sucesso',
            'dado' => $token
        ], status: 201);
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN CLUBE
    |--------------------------------------------------------------------------
    */
    public function postLoginClube(Request $request)
    {
        $Login = new LoginClubeModel(
            login: $request->login,
            senha: $request->senha,
            facebook: $request->facebook,
            google: $request->google,
            clientId: $request->client_id,
            redirectUri: $request->redirect_uri,
            state: $request->state,
            scope: $request->scope,
            audience: $request->audience
        );

        return mensagemSucesso($Login->token(), 201);
    }
}
