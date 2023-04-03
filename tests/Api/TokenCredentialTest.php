<?php

namespace Tests\Api;

use Tests\Tests;

final class TokenCredentialTest extends Tests
{
    private string $linkApi;
    public function __construct()
    {
        $this->linkApi = env('API_URL', LINK_API);
    }
    public function criandoTokenComDadosCorretosTest()
    {
        $this->curl($this->linkApi);
        $this
            ->Curl
            ->body($this->pegarBody())
            ->post('/token');

        return $this
            ->checkStatus(201)
            ->checkIndiceExiste('dado.access_token');
    }

    public function naoPodeCriarTokenSemClientIdTest()
    {
        $this->curl($this->linkApi);
        $this
            ->Curl
            ->body($this->pegarBody(clientId: ''))
            ->post('/token');

        return $this
            ->checkStatus(403)
            ->checkIndiceIgual(
                'erro.mensagem',
                'Você não tem permissão para acessar essa informação, verifique suas permissões e tente novamente.'
            )
            ->checkIndiceNaoExiste('dado.access_token');
    }
    public function naoPodeCriarTokenComClientIdErradoTest()
    {
        $this->curl($this->linkApi);
        $this
            ->Curl
            ->body($this->pegarBody(clientId: 'errado'))
            ->post('/token');

        return $this
            ->checkStatus(403)
            ->checkIndiceIgual(
                'erro.mensagem',
                'Você não tem permissão para acessar essa informação, verifique suas permissões e tente novamente.'
            )
            ->checkIndiceNaoExiste('dado.access_token');
    }

    public function naoPodeCriarTokenSemSecretIdTest()
    {
        $this->curl($this->linkApi);
        $this
            ->Curl
            ->body($this->pegarBody(secretId: ''))
            ->post('/token');

        return $this
            ->checkStatus(403)
            ->checkIndiceIgual(
                'erro.mensagem',
                'Você não tem permissão para acessar essa informação, verifique suas permissões e tente novamente.'
            )
            ->checkIndiceNaoExiste('dado.access_token');
    }
    public function naoPodeCriarTokenComSecretIdErradoTest()
    {
        $this->curl($this->linkApi);
        $this
            ->Curl
            ->body($this->pegarBody(secretId: 'errado'))
            ->post('/token');

        return $this
            ->checkStatus(403)
            ->checkIndiceIgual(
                'erro.mensagem',
                'Você não tem permissão para acessar essa informação, verifique suas permissões e tente novamente.'
            )
            ->checkIndiceNaoExiste('dado.access_token');
    }

    public function naoPodeCriarTokenSemAudienceTest()
    {
        $this->curl($this->linkApi);
        $this
            ->Curl
            ->body($this->pegarBody(audience: ''))
            ->post('/token');

        return $this
            ->checkStatus(403)
            ->checkIndiceIgual(
                'erro.mensagem',
                'Você não tem permissão para acessar essa informação, verifique suas permissões e tente novamente.'
            )
            ->checkIndiceNaoExiste('dado.access_token');
    }
    public function naoPodeCriarTokenComAudienceErradoTest()
    {
        $this->curl($this->linkApi);
        $this
            ->Curl
            ->body($this->pegarBody(audience: 'errado'))
            ->post('/token');

        return $this
            ->checkStatus(403)
            ->checkIndiceIgual(
                'erro.mensagem',
                'Você não tem permissão para acessar essa informação, verifique suas permissões e tente novamente.'
            )
            ->checkIndiceNaoExiste('dado.access_token');
    }

    public function naoPodeCriarTokenComScopeErradoTest()
    {
        $this->curl($this->linkApi);
        $this
            ->Curl
            ->body($this->pegarBody(scope: 'errado'))
            ->post('/token');

        return $this
            ->checkStatus(403)
            ->checkIndiceIgual('erro.mensagem', 'Você não tem permissão para acessar um ou mais scopes.')
            ->checkIndiceNaoExiste('dado.access_token');
    }

    /*
    |--------------------------------------------------------------------------
    | PRIVADO
    |--------------------------------------------------------------------------
    */
    public function pegarBody(
        ?string $clientId = null,
        ?string $secretId = null,
        ?string $audience = null,
        string $scope = ''
    ) {
        return [
            'client_id' => is_null($clientId) ? env('API_CLIENT_ID') : $clientId,
            'secret_id' => is_null($secretId) ? env('API_SECRET_ID') : $secretId,
            'audience' => is_null($audience) ? env('API_AUDIENCE') : $clientId,
            'grant_type' => 'client_credentials',
            'scope' => $scope,
        ];
    }
}
