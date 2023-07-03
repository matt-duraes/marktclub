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
    private string $validarState = '';
    private string $validarPkce = '';
    private string $linkErro = '';
    private string $linkClube;

    public function __construct(
        private string $code,
        private string $state
    ) {
        $this->linkClube = env('FENAE_CLUBE');
        $this->pegarCookie();
        $this->validarRequest();
        $this->setarProvider();
        $this->setarAccessToken();
        $this->validarAccessToken();
        $this->setarUsuario();
    }

    /**
     * Verifica se o usuário pode logar
     *
     * @return bool
     */
    public function podeLogar(): bool
    {
        if (!$this->usuario['filiado']) {
            $this->linkErro = $this->linkClube . '?erro=nao-filiado';
            return false;
        } elseif (
            empty($this->usuario['nome']) ||
            empty($this->usuario['cpf']) ||
            empty($this->usuario['email']) ||
            !validarCpf($this->usuario['cpf']) ||
            !validarEmail($this->usuario['email'])
        ) {
            $this->linkErro = $this->linkClube . '?erro=atualizar-cadastro';
            return false;
        }
        return true;
    }

    public function pegarLinkErro(): string
    {
        return $this->linkErro;
    }

    /**
     * Pegar um array com os dados do usuário
     */
    public function pegarUsuario(): array
    {
        return $this->usuario;
    }

    private function pegarCookie()
    {
        if (!cookieExiste('MKCTC')) {
            mensagemStatus(401, localhost: 'Cookie não existe para validar login.');
        }
        $dado = base64Decode(cookie('MKCTC'));
        cookieDeletar('MKCTC');
        $this->validarState = $dado['state'];
        $this->validarPkce = $dado['pkce'];
    }

    private function validarRequest(): void
    {
        if (
            !empty($this->validarState) &&
            !empty($this->validarPkce) &&
            $this->validarState == $this->state
        ) {
            return;
        }
        mensagemStatus(401, localhost: 'Não foi possível validar sessões.');
    }

    private function setarAccessToken(): void
    {
        $this->provider->setPkceCode($this->validarPkce);
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
            'nome'    => $usuario['name'],
            'cpf'     => (int)soNumero($usuario['cpf']),
            'email'   => strCaixaBaixa($usuario['email']),
            'grupo'   => strCaixaBaixa($usuario['type']),
            'filiado' => $usuario['affiliate'] == 1
        ];
    }
}
