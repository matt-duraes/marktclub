<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Api\ApiApp\AppEntity;
use App\Models\Api\ApiToken\TokenCredentialEntity;

final class TokenController extends Controller
{
    public function postSalvar(Request $request)
    {
        $grantType = $request->grant_type;
        if ($grantType == 'client_credentials') {
            return $this->criarCredentialToken($request);
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
                'dado' => $Token->criarToken(app: $App, scope: $scope, audience: $request->audience)
            ],
            status: 201
        );
    }
}
