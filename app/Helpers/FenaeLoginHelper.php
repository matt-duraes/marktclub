<?php

namespace App\Helpers;

use League\OAuth2\Client\Provider\GenericProvider;

final class FenaeLoginHelper
{
    private GenericProvider $provider;
    public function __construct()
    {
        $link = env('FENAE_REDIRECT_URI_BASE');
        $this->provider = new GenericProvider([
            'clientId'                => env('FENAE_CLIENT_ID'),
            'clientSecret'            => env('FENAE_CLIENT_SECRET'),
            'urlAuthorize'            => env('FENAE_LINK_AUTHORIZE'),
            'redirectUri'             => env('FENAE_REDIRECT_URI'),
            'urlAccessToken'          => '',
            'urlResourceOwnerDetails' => ''
        ]);
    }

    public function pegarLinkAutorizacao()
    {
        $url = $this->provider->getAuthorizationUrl([
            'frontChannelLogoutUri'   => env('FENAE_CHANNEL_LOGOUT_URI'),
            'postLogoutRedirectUri'   => env('FENAE_LOGOUT_REDIRECT_URI'),
            'scope'                   => env('FENAE_SCOPE')
        ]);
        $state = $this->provider->getState();
        // $pkce = $this->provider->getPkceCode();

        sessao('FENAE_LOGIN_STATE', $state);
        return $url;
    }
}
