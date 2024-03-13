<?php

namespace App\Models\Oauth\Fenae;

use Erro\Excecao;
use Helpers\CurlHelper;
use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Provider\AbstractProvider;

trait ProviderTrait
{
    private array $configuracao;
    private GenericProvider $provider;
    private string $authorizationUrl;
    private string $sessionEndUrl;

    /**
     * Seta um provider da FENAE
     *
     * @throws Excecao
     */
    private function setarProvider(): void
    {
        $this->pegarConfiguracao();
        $this->provider = new GenericProvider([
            'clientId'                => env('FENAE_CLIENT_ID'),
            'clientSecret'            => env('FENAE_CLIENT_SECRET'),
            'redirectUri'             => env('FENAE_REDIRECT_URI'),
            'urlAuthorize'            => $this->configuracao['authorization_endpoint'] ?? 'https://login.fenae.org.br/connect/authorize',
            'urlAccessToken'          => $this->configuracao['token_endpoint'] ?? 'https://login.fenae.org.br/connect/token',
            'urlResourceOwnerDetails' => $this->configuracao['userinfo_endpoint'] ?? 'https://login.fenae.org.br/connect/userinfo',
            'pkceMethod'              => AbstractProvider::PKCE_METHOD_S256,
            'scopes'                  => env('FENAE_SCOPE')
        ]);

        $postLogoutRedirectUri = '?post_logout_redirect_uri=' . env('FENAE_POST_LOGOUT_REDIRECT_URI');
        $state = '&state=' . $this->provider->getState();

        $this->authorizationUrl = $this->provider->getAuthorizationUrl();
        $this->sessionEndUrl = preg_replace(
            '/\/{1,}$/',
            '',
            $this->configuracao['end_session_endpoint'] ?? 'https://login.fenae.org.br/connect/endsession'
        ) . $postLogoutRedirectUri . $state;
    }

    /**
     * @throws Excecao
     */
    private function pegarConfiguracao(): void
    {
        if (cookieExiste('MKCLCO')) {
            $this->configuracao = base64Decode(cookie('MKCLCO'));
            // return;
        }
        $configuracao = (new CurlHelper())
            ->get(env('FENAE_LINK_CONFIGURACAO'))
            ->array();
        $this->configuracao = $configuracao;
        // cookie('MKCLCO', base64Encode($configuracao), hora: 1);
    }
}
