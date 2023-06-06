<?php

namespace App\Models\Oauth\Fenae;

use Helpers\CurlHelper;
use League\OAuth2\Client\Provider\GenericProvider;

trait ProviderTrait
{
    private array $configuracao;
    private GenericProvider $provider;
    private string $authorizationUrl;
    private string $sessionEndUrl;

    /**
     * Seta um o provide da FENAE
     */
    private function setarProvider()
    {
        $this->pegarConfiguracao();
        $this->provider = new GenericProvider([
            'clientId'                => env('FENAE_CLIENT_ID'),
            'clientSecret'            => env('FENAE_CLIENT_SECRET'),
            'redirectUri'             => env('FENAE_REDIRECT_URI'),
            'urlAuthorize'            => $this->configuracao['authorization_endpoint'],
            'urlAccessToken'          => $this->configuracao['token_endpoint'],
            'urlResourceOwnerDetails' => $this->configuracao['userinfo_endpoint'],
            'pkceMethod'              => GenericProvider::PKCE_METHOD_S256,
            'scopes'                  => env('FENAE_SCOPE')
        ]);
        $this->authorizationUrl = $this->provider->getAuthorizationUrl();
        $this->sessionEndUrl = preg_replace('/\/{1,}$/', '', $this->configuracao['end_session_endpoint'])
        . '?post_logout_redirect_uri=' . env('FENAE_POST_LOGOUT_REDIRECT_URI')
        . '&state=' . $this->provider->getState();
    }

    private function pegarConfiguracao()
    {
        if (cookieExiste('MKCLCO')) {
            $this->configuracao = base64Decode(cookie('MKCLCO'));
            return;
        }
        $Curl = new CurlHelper();
        $configuracao = $Curl->get(env('FENAE_LINK_CONFIGURACAO'))->array();
        $this->configuracao = $configuracao;
        cookie('MKCLCO', base64Encode($configuracao), hora: 1);
    }
}
