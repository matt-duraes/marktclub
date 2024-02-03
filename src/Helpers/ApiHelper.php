<?php

namespace Helpers;

use Erro\Excecao;

class ApiHelper extends CurlHelper
{
    protected string $clientId;
    protected string $secretId;
    protected string $audience;

    /**
     * Passar scope caso queira autenticar a requisição
     *
     * @param  string|null $scope Scope que deseja acessar
     * @param  string|bool $token Passe um token para ser usado ou true para usar o token da sessão
     * @throws Excecao
     */
    public function __construct(
        string $scope = null,
        string|bool $token = false,
        string $clientId = null,
        string $secretId = null,
        string $audience = null
    ) {
        $this->clientId = !empty($clientId) ? $clientId : env('API_CLIENT_ID');
        $this->secretId = !empty($secretId) ? $secretId : env('API_SECRET_ID');
        $this->audience = !empty($audience) ? $audience : env('API_AUDIENCE');

        $this->apiHelper = true;

        parent::__construct(env('API_LINK', LINK_API));
        if (!empty($scope)) {
            $this->autenticar($scope . ' admin:chave_publica admin:chave_privada');
        } elseif (is_bool($token) && $token) {
            $this->header(['Authorization' => 'Bearer ' . sessao('TOKEN')]);
        } elseif (!empty($token)) {
            $this->header(['Authorization' => 'Bearer ' . $token]);
        }
    }

    /**
     * @param  string  $scope Scope que deseja acessar
     * @throws Excecao
     */
    private function autenticar(string $scope): void
    {
        $token = $this->body([
            'client_id'  => $this->clientId,
            'secret_id'  => $this->secretId,
            'audience'   => $this->audience,
            'grant_type' => 'client_credentials',
            'scope'      => $scope
        ])->post('/token')->array();

        $this->resetar();
        if (array_key_exists('status', $token) && $token['status'] === 'sucesso') {
            $this->header(['Authorization' => 'Bearer ' . $token['dado']['access_token']]);
            return;
        }
        throw new Excecao(status: 401);
    }
}
