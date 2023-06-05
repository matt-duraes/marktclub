<?php

namespace App\Models\Oauth\Fenae;

use Helpers\CurlHelper;
use League\OAuth2\Client\Provider\GenericProvider;

trait ProviderTrait
{
    private array $configuracao;
    private GenericProvider $provider;

    private function setarProvider()
    {
        $this->pegarConfiguracao();
        $this->provider = new GenericProvider([
            'clientId'                => env('FENAE_CLIENT_ID'),
            'clientSecret'            => env('FENAE_CLIENT_SECRET'),
            'redirectUri'             => env('FENAE_REDIRECT_URI'),
            'scopes'                  => env('FENAE_SCOPES'),
            'urlAuthorize'            => $this->configuracao['authorization_endpoint'],
            'urlAccessToken'          => $this->configuracao['token_endpoint'],
            'pkceMethod'              => GenericProvider::PKCE_METHOD_S256,
            'urlResourceOwnerDetails' => ''
        ]);
    }

    private function pegarConfiguracao()
    {
        $Curl = new CurlHelper();
        $this->configuracao = $Curl->get(env('FENAE_LINK_CONFIGURACAO'))->array();
    }
}
