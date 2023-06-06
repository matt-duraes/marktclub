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
        $this->setarSessao();
        $this->validarCriacaoUrl();
        $this->pegarIdToken();
    }
    private function setarSessao(): void
    {
        sessao('FENAE_LOGIN_STATE', $this->provider->getState());
    }
    private function validarCriacaoUrl(): void
    {
        if (
            sessaoExiste('FENAE_LOGIN_STATE') ||
            !empty(sessao('FENAE_LOGIN_STATE'))
        ) {
            return;
        }

        mensagemErro(
            titulo: 'ERRO!',
            // @codingStandardsIgnoreStart
            mensagem: 'Ocorreu um erro ao fazer seu login, tente novamente, caso o erro continue, entre em contato com o atendimento.',
            // @codingStandardsIgnoreEnd
            status: 500
        );
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
