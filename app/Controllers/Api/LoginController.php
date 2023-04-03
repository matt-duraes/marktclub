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
        $payload = criptografarDado(
            dado: [
                'sub' => $Usuario->id,
                'name' => $Usuario->nome->nome(),
                'picture' => $Usuario->imagem,
                'email' => $Usuario->email->email(),
                'email_verified' => 'nao',
                'create_at' => $Usuario->data_criacao->date(),
                'updated_at' => $Usuario->data_atualizacao->date(),
            ],
            criptografia: ['name', 'picture', 'email']
        );

        return $this->criarToken($payload, $request, new Tipo(Tipo::PAINEL));
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
