<?php

namespace App\Models\Oauth\Fenae;

use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Provider\GenericProvider;

final class PegarTokenModel
{
    use ProviderTrait;

    private GenericProvider $provider;
    private AccessToken $accessToken;
    private array $usuario;

    public function __construct(
        private string $code,
        private string $state
    ) {
        $this->validarRequest();
        $this->setarProvider();
        $this->setarAccessToken();
        $this->validarAccessToken();
        $this->setarUsuario();
        $this->deletarSessao();
    }

    /**
     * Pegar um array com os dados do usuário
     */
    public function pegarUsuario(): array
    {
        return $this->usuario;
    }

    private function validarRequest(): void
    {
        if (
            sessaoExiste('FENAE_LOGIN_PKCE') &&
            sessaoExiste('FENAE_LOGIN_STATE') &&
            sessao('FENAE_LOGIN_STATE') == $this->state
        ) {
            return;
        }
        mensagemStatus(401, localhost: 'Não foi possível validar sessões.');
    }
    private function setarAccessToken(): void
    {
        $this->provider->setPkceCode(sessao('FENAE_LOGIN_PKCE'));
        $this->accessToken = $this->provider->getAccessToken('authorization_code', [
            'code' => $this->code
        ]);
    }
    private function validarAccessToken(): void
    {
        if (!$this->accessToken->hasExpired()) {
            return;
        }
        mensagemStatus(403);
    }
    private function setarUsuario()
    {
        $idToken = $this->accessToken->getValues()['id_token'] ?? '';
        if (empty($idToken)) {
            mensagemStatus(401, localhost: 'Não foi possível pegar o usuário.');
        }

        cookie('MKCLTI', base64Encode($idToken), dia: 1);
        $usuario = $this->provider->getResourceOwner($this->accessToken)->toArray();
        $this->usuario = [
            'nome' => $usuario['name'],
            'cpf' => (int)soNumero($usuario['cpf']),
            'email' => strCaixaBaixa($usuario['email']),
            'grupo' => strCaixaBaixa($usuario['type'])
        ];
    }
    private function deletarSessao()
    {
        sessaoDeletar('FENAE_LOGIN_PKCE');
        sessaoDeletar('FENAE_LOGIN_STATE');
    }
}
