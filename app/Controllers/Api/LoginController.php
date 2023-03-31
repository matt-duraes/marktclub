<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Classes\ApiToken\Tipo;
use App\Classes\UsuarioCliente\Helper;
use App\Models\Api\LoginApi\DigioModel;
use App\Models\Api\UsuarioCliente\ClienteEntity;
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
            'dado' => $token
        ], status: 201);
    }
}
