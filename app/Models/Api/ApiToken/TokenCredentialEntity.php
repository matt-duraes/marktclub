<?php

namespace App\Models\Api\ApiToken;

use ORM\Entity;
use Helpers\JwtHelper;
use App\Models\Api\ApiApp\AppEntity;
use App\Models\Api\ApiUsuario\UsuarioEntity;

final class TokenCredentialEntity extends Entity
{

    use Trait\ScopeTrait;
    protected string $_tabela = TABELA_AUTH_TOKEN;

    public function criarToken(
        AppEntity $app,
        array $scope,
        string $audience
    ) {
        $scope = $this->pegarScope($scope, $app->scope_permitido);
        $jwt = $this->criarJwt($app, $audience, $scope);

        return [
            'access_token' => $jwt,
            'scope' => implode(' ', $scope),
            'expires_in' => $app->tempo_vida,
            'token_type' => 'Bearer',
        ];
    }

    private function criarJwt(AppEntity $app, $audience, $scope)
    {

        $criado = time();
        $vencimento = time() + $app->tempo_vida;

        $idApp = $app->get('id');
        try {
            $Usuario = new UsuarioEntity();
            $Usuario->buscar([
                ['id_api_app', 'like', '%"' . $idApp . '"%'],
                ['status', 1]
            ]);
        } catch (\Throwable) {
            mensagemStatus(403);
        }

        $payload = [
            'iss' => LINK,
            'sub' => $Usuario->login_usuario,
            'aud' => $audience,
            'iat' => $criado,
            'exp' => $vencimento,
            'azp' => $app->client_id,
            'scope' => implode(' ', $scope),
            'gty' => 'client-credentials',
        ];

        try {
            return (new JwtHelper())->encode($payload);
        } catch (\Throwable) {
            mensagemStatus(500);
        }
    }
}
