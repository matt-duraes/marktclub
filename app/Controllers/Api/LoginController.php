<?php

namespace App\Controllers\Api;

use Throwable;
use Http\Request;
use Http\Response;
use Modules\Botao;
use Controller\Controller;
use App\Classes\ApiToken\Tipo;
use App\Classes\UsuarioCliente\Helper;
use App\Models\Api\LoginApi\DigioModel;
use App\Models\Api\LoginClube\LoginClubeModel;
use App\Models\Api\LoginPainel\LoginPainelModel;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use App\Classes\LoginClube\Tipo as LoginClubeTipo;
use App\Models\Api\ConstrutorClube\ConstrutorEntity;
use App\Models\Api\ApiToken\TokenAuthorizationEntity;
use App\Models\Api\LoginApi\OauthModel as LoginOauth;
use App\Models\Api\LoginApi\LoginModel as LoginApiModel;
use App\Models\Api\LoginApi\PositivoModel as LoginPositivo;

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

    public function postLoginOauth(Request $request): Response
    {
        $Login = new LoginOauth(
            empresa: $request->empresa,
        );
        return mensagemSucesso(dado: $Login->retorno, status: 201);
    }

    public function postLoginPositivo(Request $request): Response
    {
        $Login = new LoginPositivo($request->dado());
        return mensagemSucesso(dado: $Login->retorno, status: $Login->status);
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
            state: $request->state,
            tipo: new LoginClubeTipo($request->tipo),
            cadastro: new Botao($request->cadastro),
            termo: new Botao($request->termo)
        );

        return mensagemSucesso([
            'token' => $Login->token,
            'clube' => $Login->construtor
        ]);
    }

    public function postLoginHash(Request $request)
    {
        $Login = new LoginClubeModel(
            hash: $request->hash,
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
        $Login = new LoginPainelModel(
            login: $request->login,
            senha: $request->senha,
            audience: $request->audience,
            redirectUri: $request->redirect_uri,
            state: $request->state,
        );

        return new Response(json: [
            'status' => 'sucesso',
            'dado'   => $Login->token
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
        } catch (Throwable) {
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
        } catch (Throwable) {
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
            $Usuario->id_admin_empresa,
            new Tipo(TIPO::CLUBE)
        );

        return new Response(json: [
            'status' => 'sucesso',
            'dado'   => $token
        ], status: 201);
    }
}
