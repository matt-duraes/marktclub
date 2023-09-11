<?php

namespace Helpers;

use Erro\Excecao;

class ApiHelper extends CurlHelper
{
    public CryptHelper $Crypt;
    protected string $clientId;
    protected string $secretId;
    protected string $audience;

    /**
     * Passar scope caso queira autenticar a requisição
     *
     * @param  string|null $scope Scope que deseja acessar
     * @param  string|bool $token Passe um token para ser usado ou true para usar o token da sessão
     * @throws Excecao
     */
    public function __construct(string $scope = null, string|bool $token = false)
    {
        $this->clientId = env('API_CLIENT_ID');
        $this->secretId = env('API_SECRET_ID');
        $this->audience = env('API_AUDIENCE');

        $this->apiHelper = true;

        parent::__construct(env('API_LINK', LINK_API));

        if (!empty($scope)) {
            $this->autenticar($scope);
        } elseif (is_bool($token) && $token) {
            $this->header(['Authorization' => 'Bearer ' . sessao('TOKEN')]);
        } elseif (!empty($token)) {
            $this->header(['Authorization' => 'Bearer ' . $token]);
        }

        $this->setarCryptHelper();
    }

    private function setarCryptHelper()
    {
        if (!sessaoExiste('CRYPT_CHAVE_PUBLICA')) {
            $chave = $this
                ->get('/admin/chave-publica')
                ->object()->dado->chave ?? '';
            sessao('CRYPT_CHAVE_PUBLICA', $chave);
        }
        if (!sessaoExiste('CRYPT_CHAVE_PRIVADA')) {
            $chave = $this
                ->get('/admin/chave-privada')
                ->object()->dado->chave ?? '';
            sessao('CRYPT_CHAVE_PRIVADA', $chave);
        }

        $this->Crypt = new CryptHelper(
            chavePublica: sessao('CRYPT_CHAVE_PUBLICA'),
            chavePrivada: sessao('CRYPT_CHAVE_PRIVADA')
        );
    }

    /**
     * @param  string  $scope Scope que deseja acessar
     * @throws Excecao
     */
    private function autenticar(string $scope): void
    {
        $token = $this->body([
            'client_id'  => $this->clientId,
            'secret_id'  => $this->secretId,
            'audience'   => $this->audience,
            'grant_type' => 'client_credentials',
            'scope'      => $scope
        ])->post('/token')->array();

        $this->resetar();
        if (array_key_exists('status', $token) && $token['status'] === 'sucesso') {
            $this->header(['Authorization' => 'Bearer ' . $token['dado']['access_token']]);
            return;
        }

        throw new Excecao(status: 401);
    }
}
