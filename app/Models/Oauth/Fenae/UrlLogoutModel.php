<?php

namespace App\Models\Oauth\Fenae;

use League\OAuth2\Client\Provider\GenericProvider;

final class UrlLogoutModel
{
    use ProviderTrait;

    private GenericProvider $provider;
    private string $url;
    private string $uriIdToken = '';

    public function __construct()
    {
        $this->setarProvider();
        $this->setarCookie();
        $this->pegarIdToken();
    }

    private function setarCookie(): void
    {
        cookie('MKCLOE', base64Encode($this->provider->getState()), minuto: 10);
    }

    private function pegarIdToken()
    {
        if (!cookieExiste('MKCLTI')) {
            return;
        }
        $id = base64Decode(cookie('MKCLTI'));
        if (empty($id)) {
            return;
        }
        $this->uriIdToken = '&id_token_hint=' . $id;
    }

    /**
     * Pega a URL de logout do sistema
     *
     * @return string
     */
    public function pegarUrlLogout(): string
    {
        return $this->sessionEndUrl . $this->uriIdToken;
    }
}
