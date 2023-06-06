<?php

namespace App\Models\Oauth\Fenae;

use League\OAuth2\Client\Provider\GenericProvider;

final class UrlLoginModel
{
    use ProviderTrait;

    private GenericProvider $provider;
    private string $url;

    public function __construct()
    {
        $this->setarProvider();
        $this->criarCookie();
    }
    private function criarCookie(): void
    {
        cookie('MKCTC', base64Encode([
            'state' => $this->provider->getState(),
            'pkce' => $this->provider->getPkceCode()
        ]), minuto: 10);
    }
    public function pegarUrlLogin(): string
    {
        return $this->authorizationUrl;
    }
}
