<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Classes\ApiToken\Tipo;
use App\Classes\UsuarioCliente\Helper;
use App\Models\Api\LoginApi\DigioModel;
use App\Models\Api\LoginPainel\LoginFormModel;
use App\Models\Api\LoginPainel\LoginGoogleModel;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use App\Classes\ApiToken\Helper as ApiTokenHelper;
use App\Models\Api\LoginPainel\LoginFacebookModel;
use App\Models\Api\AdminConstrutor\ConstrutorEntity;
use App\Models\Api\ApiToken\TokenAuthorizationEntity;
use App\Models\Api\LoginApi\LoginModel as LoginApiModel;
use App\Models\Api\LoginClube\LoginModel as LoginClubeModel;

final class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LOGIN DIGIO
    |--------------------------------------------------------------------------
    */
    public function postLoginDigio(Request $request)
    {
        $Digio = new DigioModel($request->usuario);
        return $Digio->link();
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN CLUBE
    |--------------------------------------------------------------------------
    */
    public function postLoginApi(Request $request)
    {
        $Login = new LoginApiModel($request);
        return $Login->link();
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
        $payload = criptografarDado([
            'sub' => $Usuario->id,
            'name' => $Usuario->nome->nome(),
            'picture' => $Usuario->imagem,
            'email' => $Usuario->email->email(),
            'email_verified' => 'nao',
            'create_at' => $Usuario->data_criacao->date(),
            'updated_at' => $Usuario->data_atualizacao->date(),
        ], lista: ['name', 'picture', 'email']);

        return $this->criarToken($payload, $request, new Tipo(Tipo::TIPO_PAINEL));
    }

    private function criarToken(array $body, Request $request, Tipo $tipo): Response
    {
        $Token = new TokenAuthorizationEntity();
        $token = $Token->criarToken(
            TOKEN['app'],
            $body,
            empty($request->scope) ? [] : explode(' ', $request->scope),
            $request->audience,
            $request->redirect_uri,
            $request->state,
            $tipo
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

    /*
    |--------------------------------------------------------------------------
    | LOGIN CLUBE TOKEN
    |--------------------------------------------------------------------------
    */
    public function postLoginToken(Request $request)
    {
        if ($request->vazio('usuario')) {
            mensagemErro('Campo obrigatório!', 'Você deve passar um usuário para continuar.');
        } else if ($request->vazio('clube')) {
            mensagemErro('Campo obrigatório!', 'Você deve passar um clube para continuar.');
        }

        try {
            $Construtor = new ConstrutorEntity();
            $Construtor->id($request->clube);
        } catch (\Throwable) {
            mensagemErro('Erro!', 'Clube não encontrado.', status: 404);
        }

        $idEmpresa = $Construtor->id_admin_empresa;
        $Usuario = new ClienteEntity(validarToken: false);

        try {
            $Usuario->buscar([
                ['cod', $request->usuario],
                ['status', 'in', Helper::STATUS_LIBERADO],
                [
                    'OR',
                    ['empresa', $idEmpresa],
                    [
                        ['empresa', 1],
                        ['tipo', 3]
                    ]
                ]
            ]);
        } catch (\Throwable) {
            mensagemErro('Erro!', 'Usuário não encontrado.', status: 404);
        }

        $body = [
            'sub' => $Usuario->id
        ];

        $Token = new TokenAuthorizationEntity();
        $token = $Token->criarToken(
            TOKEN['app'],
            $body,
            [],
            env('API_AUDIENCE', ''),
            env('API_REDIRECT_URI', ''),
            uuid(),
            new Tipo(TIPO::TIPO_CLUBE)
        );

        return new Response(json: [
            'status' => 'sucesso',
            'dado' => $token
        ], status: 201);
    }
}
