<?php

namespace App\Middlewares\Api;

use Throwable;
use Erro\Excecao;
use Helpers\JwtHelper;
use App\Models\Api\ApiToken\ValidarTokenAntigoEntity;
use App\Models\Api\ApiToken\ValidarTokenCredentialModel;
use App\Models\Api\ApiToken\ValidarTokenAuthorizationEntity;

final class TokenMiddleware
{
    private bool $old = false;
    private string $token;
    private array $body = [];
    private string $tipoToken;

    public function __construct()
    {
        $header = getallheaders();
        $this->token = $header['Authorization']
            ?? $header['authorization']
            ?? $_SERVER['HTTP_AUTHORIZATION']
            ?? false;

        $this->validarTokenEnviado();
        $this->pegarBody();
        $this->tipoToken = $this->pegarTipoDeToken();
    }

    /**
     */
    private function validarTokenEnviado(): void
    {
        $token = $this->token;
        if (empty($token)) {
            $this->erroToken('Middleware Token - Token vazio.');
        } elseif (str_starts_with($token, 'Old ') && mb_strlen($this->token) == 40) {
            $this->old = true;
            $this->token = preg_replace('/^Old /', '', $this->token);
            return;
        } elseif (!str_starts_with($token, 'Bearer ')) {
            $this->erroToken('Middleware Token - Token não começa com Bearer.');
        }
        $this->token = preg_replace('/^Bearer /', '', $this->token);

        if (mb_strlen($this->token) != 36 && !(new JwtHelper())->validar($this->token)) {
            $this->erroToken('Middleware Token - Não foi possível validar token.');
        }
    }

    /**
     * @param $mensagem
     *
     * @throws Excecao
     */
    private function erroToken($mensagem): void
    {
        mensagemErro(
            'Token inválido!',
            'Envie um token válido para autenticação.',
            401,
            localhost: $mensagem
        );
    }

    /**
     * @throws Excecao
     */
    private function pegarBody(): void
    {
        if (mb_strlen($this->token) == 36) {
            return;
        }
        try {
            $Jwt = new JwtHelper();
            $this->body = $Jwt->decode($this->token);
        } catch (Throwable $e) {
            $this->erroToken('Middleware Token - Erro ao pegar body do token - ' . $e->getMessage() . '.');
        }
    }

    /**
     * @return string|void
     * @throws Excecao
     */
    private function pegarTipoDeToken()
    {
        if (mb_strlen($this->token) == 36) {
            return 'authorization';
        } elseif (array_key_exists('gty', $this->body) && $this->body['gty'] == 'client-credentials') {
            return 'client-credentials';
        }
        $this->erroToken('Middleware Token - Token não tem 36 caracteres ou é um JWT.');
    }

    /**
     * @return bool|void
     * @throws Excecao
     */
    public function token()
    {
        $tipo = $this->tipoToken;
        if ($tipo == 'client-credentials') {
            $Token = new ValidarTokenCredentialModel();
            return $Token->validar($this->token);
        } elseif ($tipo == 'authorization') {
            if ($this->old) {
                $Token = new ValidarTokenAntigoEntity();
            } else {
                $Token = new ValidarTokenAuthorizationEntity();
            }
            try {
                $Token->buscar([
                    ['access_token', $this->token],
                    ['status', 1]
                ]);
                return true;
            } catch (Throwable $e) {
                mensagemStatus(
                    401,
                    error: $e,
                    localhost: 'Middleware Token - Não foi possível achar seu token ou o status dele não é 1'
                );
            }
        }

        mensagemStatus(500, localhost: 'Middleware Token - Tipo de token inválido.');
    }

    /**
     * @param $scope
     *
     * @return bool
     * @throws Excecao
     */
    public function scope($scope): bool
    {
        $scopePermitido = TOKEN['scope'];
        if (!in_array($scope, $scopePermitido)) {
            mensagemErro(
                'Erro de permissão!',
                'Você não tem permissão para acessar esse scope.',
                403,
                localhost: 'Middleware Token - Seu token não tem o scope para essa ação.'
            );
        }
        define('TOKEN_SCOPE', $scope);
        return true;
    }

    /**
     * @return true|void
     * @throws Excecao
     */
    public function login()
    {
        if ($this->tipoToken == 'authorization') {
            return true;
        }
        $this->erroToken('Middleware Token - Não é um token authorization.');
    }
}
