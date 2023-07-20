<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Classes\ApiToken\Tipo;
use App\Classes\UsuarioCliente\Helper;
use App\Models\Api\LoginApi\DigioModel;
use App\Models\Api\ApiToken\PayloadModel;
use App\Models\Api\LoginClube\LoginClubeModel;
use App\Models\Api\LoginPainel\LoginFormModel;
use App\Models\Api\LoginPainel\LoginGoogleModel;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use App\Models\Api\LoginPainel\LoginFacebookModel;
use App\Models\Api\AdminConstrutor\ConstrutorEntity;
use App\Models\Api\ApiToken\TokenAuthorizationEntity;
use App\Models\Api\LoginApi\LoginModel as LoginApiModel;

final class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LOGIN DIGIO
    |--------------------------------------------------------------------------
    */
    public function postLoginDigio(Request $request)
    {
        $Digio = new DigioModel(
            usuario: $request->usuario,
            clube: $request->clube
        );
        return $Digio->link();
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN CLUBE
    |--------------------------------------------------------------------------
    */
    public function postLoginApi(Request $request)
    {
        $Login = new LoginApiModel($request->dado());
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
    | LOGIN CLUBE
    |--------------------------------------------------------------------------
    */
    public function postLoginClube(Request $request)
    {
        $Login = new LoginClubeModel(
            login: $request->login,
            senha: $request->senha,
            redirectUri: $request->redirect_uri,
            state: $request->state
        );

        return mensagemSucesso([
            'token' => $Login->token,
            'clube' => $Login->construtor
        ]);
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
        } elseif (!empty($dado->google)) {
            $Login = new LoginGoogleModel($dado->google);
        } else {
            $Login = new LoginFormModel($dado->login, $dado->senha);
        }

        $Usuario = $Login->pegarUsuario();
        $payload = (new PayloadModel($Usuario, 'web'))->payload;

        return $this->criarToken(
            body: $payload,
            audience: $request->audience,
            redirectUri: $request->redirect_uri,
            state: $request->state,
            scope: $request->scope,
            tipo: new Tipo(Tipo::PAINEL)
        );
    }

    private function criarToken(
        array $body,
        string $audience,
        string $redirectUri,
        string $state,
        string $scope,
        Tipo $tipo
    ): Response {
        $Token = new TokenAuthorizationEntity();
        $token = $Token->criarToken(
            TOKEN['app'],
            $body,
            empty($scope) ? [] : explode(' ', $scope),
            $audience,
            $redirectUri,
            $state,
            $tipo
        );

        return new Response(json: [
            'status' => 'sucesso',
            'dado'   => $token
        ], status: 201);
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
        } elseif ($request->vazio('clube')) {
            mensagemErro('Campo obrigatório!', 'Você deve passar um clube para continuar.');
        }

        try {
            $Construtor = new ConstrutorEntity();
            $Construtor->uuid($request->clube);
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
            new Tipo(TIPO::CLUBE)
        );

        return new Response(json: [
            'status' => 'sucesso',
            'dado'   => $token
        ], status: 201);
    }
}
