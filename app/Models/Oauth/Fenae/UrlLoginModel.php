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
        $this->setarSessoes();
        $this->validarCriacaoUrl();
    }
    private function setarSessoes(): void
    {
        sessao('FENAE_LOGIN_STATE', $this->provider->getState() ?? '');
        sessao('FENAE_LOGIN_PKCE', $this->provider->getPkceCode() ?? '');
    }
    private function validarCriacaoUrl(): void
    {
        if (
            sessaoExiste('FENAE_LOGIN_STATE') &&
            sessaoExiste('FENAE_LOGIN_PKCE') &&
            !empty(sessao('FENAE_LOGIN_STATE')) &&
            !empty(sessao('FENAE_LOGIN_PKCE'))
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
    public function pegarUrlLogin(): string
    {
        return $this->authorizationUrl;
    }
}
