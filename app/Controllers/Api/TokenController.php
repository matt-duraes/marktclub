<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Api\ApiApp\AppEntity;
use App\Models\Api\ApiToken\RefreshTokenModel;
use System\Interface\ControllerSalvarInterface;
use App\Models\Api\ApiToken\TokenCredentialEntity;

final class TokenController extends Controller implements
    ControllerSalvarInterface
{
    public function postSalvar(Request $request): Response
    {
        $grantType = $request->grant_type;
        if ('client_credentials' == $grantType) {
            return $this->criarCredentialToken($request);
        } elseif ('refresh_token' == $grantType) {
            return $this->criarRefreshToken($request);
        }
        mensagemStatus(400);
    }

    private function criarCredentialToken($request): Response
    {
        $App = new AppEntity();
        try {
            $App->buscar([
                ['client_id', $request->client_id],
                ['secret_id', $request->secret_id],
                ['audience', $request->audience],
                ['client_credentials', 1],
                ['status', 1]
            ]);
        } catch (\Throwable) {
            mensagemStatus(403);
        }

        $Token = new TokenCredentialEntity();
        $scope = empty($request->scope) ? [] : explode(' ', $request->scope);
        return new Response(
            json: [
                'status' => 'sucesso',
                'dado'   => $Token->criarToken(app: $App, scope: $scope, audience: $request->audience)
            ],
            status: 201
        );
    }

    private function criarRefreshToken($request): Response
    {
        try {
            $App = new AppEntity();
            $App->buscar([
                ['client_id', $request->client_id],
                ['secret_id', $request->secret_id],
                ['client_credentials', 1],
                ['status', 1]
            ]);
        } catch (\Throwable $e) {
            (new RefreshTokenModel())->tokenVencido($e, 'Não foi possível encontrar o APP');
        }

        $Token = new RefreshTokenModel(
            App: $App,
            refreshToken: $request->refresh_token,
            scope: $request->scope
        );

        $retorno = $Token->pegarToken();
        if ($Token->clube) {
            $retorno = [
                'token' => $retorno,
                'clube' => $Token->clube
            ];
        }

        return new Response(json: [
            'status' => 'sucesso',
            'dado'   => $retorno
        ], status: 201);
    }
}
