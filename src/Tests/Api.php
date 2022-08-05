<?php

namespace Tests;

use Helpers\ApiHelper;
use Helpers\CurlHelper;
use Helpers\CryptHelper;

final class Api extends ApiHelper
{
    private string $redirectUri;

    public function __construct(?string $scope = null, string|bool $token = false)
    {
        $this->redirectUri = env('API_REDIRECT_URI');
        parent::__construct($scope, $token);
    }

    public function loginPainel(?string $login = null, ?string $senha = null)
    {
        if (empty($login) && empty($senha) && sessaoExiste('TOKEN_LOGIN_PAINEL_TEST')) {
            $this->header(['Authorization' => 'Bearer ' . sessao('TOKEN_LOGIN_PAINEL_TEST')]);
            return $this;
        }

        $Curl = new ApiHelper('admin:chave_publica');
        $chave = $Curl->get('/admin/chave-publica')->object()->dado->chave ?? '';

        $Crypt = new CryptHelper(chavePublica: $chave);
        $token = $this->body([
            'login' => $Crypt->encode($login),
            'senha' => $Crypt->encode($senha),
            'facebook' => '',
            'google' => '',
            'scope' => '',
            'audience' => $this->audience,
            'redirect_uri' => $this->redirectUri,
            'state' => uuid()
        ])->post('/login/painel')->array();
        $this->resetar();

        if (array_key_exists('status', $token) && $token['status'] == 'sucesso') {
            sessao('TOKEN_LOGIN_PAINEL_TEST', $token['dado']['access_token']);
            $this->header(['Authorization' => 'Bearer ' . $token['dado']['access_token']]);
            return $this;
        }

        return $this;
    }

    public function post(string $url): self
    {
        $this->curl('POST', $url);
        return $this;
    }

    public function put(string $url): self
    {
        $this->curl('PUT', $url);
        return $this;
    }

    public function get(string $url): self
    {
        $this->headerJson();
        $this->curl('GET', $url);
        return $this;
    }
    public function delete(string $url): self
    {
        $this->curl('DELETE', $url);
        return $this;
    }

    public function patch(string $url): self
    {
        $this->curl('PATCH', $url);
        return $this;
    }
}
