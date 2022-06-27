<?php

namespace App\Models\Api\ApiToken;

use ORM\Entity;
use Helpers\JwtHelper;
use App\Models\Api\ApiApp\AppEntity;

final class TokenAuthorizationEntity extends Entity
{
    use Trait\ScopeTrait;
    use Trait\RedirectUriTrait;

    protected string $_tabela = TABELA_AUTH_TOKEN;

    // protected array $_buscar = [];
    protected array $_insert = [
        'id_usuario', 'id_api_app', 'redirect_uri', 'scope_permitido', 'state_cliente', 'authorization_code',
        'access_token', 'grant_type', 'ip', 'sistema_operacional', 'navegador', 'data_ativacao', 'data_vencimento',
        'status', 'refresh_token', 'hash'
    ];

    public function criarToken(
        AppEntity $app,
        array $body,
        array $scope,
        string $audience,
        string $redirectUri,
        string $state,
        bool $logado = false
    ) {
        if (!in_array($redirectUri, $app->redirect_uri)) {
            mensagemErro('Erro!', 'Redirect Uri não está autorizado a criar token.', 403);
        } else if (empty($audience)) {
            mensagemErro('Campo incorreto!', 'Não foi enviado o audience do app.');
        } else if (empty($state)) {
            mensagemErro('Campo incorreto!', 'Não foi enviado o state do usuário.');
        }

        $tempoVida = 86400;
        $scope = $this->pegarScope($scope, $app->scope_permitido);
        $jwt = $this->criarJwt($body, $app, $audience, $scope, $tempoVida);

        $accessToken = uuid();
        $refreshToken = $logado ? uuid() : '';
        $this->salvarToken($accessToken, $refreshToken, $body, $app, $scope, $redirectUri, $state);

        $token = [
            'access_token' => $accessToken,
            'id_token' => $jwt,
            'scope' => implode(' ', $scope),
            'expires_in' => $tempoVida,
            'token_type' => 'Bearer',
        ];
        if ($logado) {
            $token['refresh_token'] = $refreshToken;
        }

        return $token;
    }

    private function salvarToken($accessToken, $refreshToken, $body, $app, $scope, $redirectUri, $state)
    {

        $this->id_usuario = $body['sub'];
        $this->id_api_app = $app->get('id');
        $this->redirect_uri = $redirectUri;
        $this->scope_permitido = $scope;
        $this->state_cliente = $state;
        $this->authorization_code = uuid();
        $this->access_token = $accessToken;
        $this->grant_type = 'implicit';
        $this->ip = ip();
        $this->sistema_operacional = '';
        $this->navegador = '';
        $this->data_ativacao = agora();
        $this->data_vencimento = date('Y-m-d H:i:s', time() + 86400);
        $this->hash = uuid();
        $this->status = 1;
        if (!empty($refreshToken)) {
            $this->refresh_token = $refreshToken;
        }
        try {
            $this->salvar();
        } catch (\Throwable) {
            mensagemErro(
                'Erro!',
                'Ocorreu um erro ao salvar seu token.',
                localhost: 'Ocorreu um erro ao salvar seu token no TokenAuthorizationEntity.'
            );
        }
    }

    private function criarJwt($body, $app, $audience, $scope, $tempoVida)
    {
        $criado = time();
        $payload = [
            'iss' => LINK,
            'aud' => $audience,
            'iat' => $criado,
            'exp' => time() + $tempoVida,
            'azp' => $app->client_id,
            'scope' => implode(' ', $scope),
            'gty' => 'implicit',
        ];
        $payload = array_merge($payload, $body);

        try {
            return (new JwtHelper)->encode($payload, $app->id);
        } catch (\Throwable) {
            mensagemStatus(500, localhost: 'TokenAuthorizationEntity - Não foi possivel criar o JWT.');
        }
    }
}
