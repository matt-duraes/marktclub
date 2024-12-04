<?php

namespace System\Html\Postman\Models\Trait;

trait TokenCredentialEntityTrait
{
    private function gerarTokenPadrao($scope)
    {
        $token = $this->enviarCurl(
            'POST',
            '{{LINK}}/token',
            [
                'client_id'  => env('POSTMAN_API_CLIENT_ID'),
                'secret_id'  => env('POSTMAN_API_SECRET_ID'),
                'audience'   => env('POSTMAN_API_AUDIENCE'),
                'grant_type' => 'client_credentials',
                'scope'      => $scope
            ]
        );

        return $this->pegarToken($token);
    }

    private function pegarToken($token)
    {
        $retorno = jsonDecode($token->retorno, true, true);
        $token = $retorno['dado']['access_token'] ?? '';
        if (!empty($token)) {
            return $token;
        }
        mensagemErro(
            $retorno['erro']['titulo'] ?? 'Erro!',
            $retorno['erro']['mensagem'] ?? 'Erro ao tentar gerar token.',
            status: 401
        );
    }
}
