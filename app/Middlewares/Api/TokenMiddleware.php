<?php

namespace App\Middlewares\Api;

use Helpers\JwtHelper;
use App\Models\Api\ApiToken\ValidarTokenCredentialModel;
use App\Models\Api\ApiToken\ValidarTokenAuthorizationEntity;

final class TokenMiddleware
{
    private string $token;
    private array $body = [];
    private string $tipoToken;

    public function __construct()
    {
        $header = getallheaders();
        $this->token = $header['Authorization'] ??
            $header['authorization'] ??
            $_SERVER['HTTP_AUTHORIZATION'] ??
            false;

        $this->validarTokenEnviado();
        $this->pegarBody();
        $this->tipoToken = $this->pegarTipoDeToken();
    }

    public function token()
    {
        $tipo = $this->tipoToken;
        if ($tipo == 'client-credentials') {
            $Token = new ValidarTokenCredentialModel();
            return $Token->validar($this->token);
        } else if ($tipo == 'authorization') {
            $Token = new ValidarTokenAuthorizationEntity();
            try {
                $Token->buscar([
                    ['access_token', $this->token],
                    ['status', 1]
                ]);
                return true;
            } catch (\Throwable $e) {
                mensagemStatus(
                    401,
                    localhost: 'Middleware Token - Não foi possível achar seu token ou o status dele não é 1',
                    error: $e
                );
            }
        }

        mensagemStatus(500, localhost: 'Middleware Token - Tipo de token inválido.');
    }

    public function scope($scope)
    {
        $scopePermitido = TOKEN['scope'];
        if (!in_array($scope, $scopePermitido)) {
            mensagemErro('Erro de permissão!', 'Você não tem permissão para acessar esse scope.', 403, localhost: 'Middleware Token - Seu token não tem o scope para essa ação.');
        }
        define('TOKEN_SCOPE', $scope);
        return true;
    }

    public function login()
    {
        if ($this->tipoToken == 'authorization') {
            return true;
        }
        $this->erroToken('Middleware Token - Não é um token authorization.');
    }

    private function validarTokenEnviado()
    {
        $token = $this->token;
        if (empty($token)) {
            $this->erroToken('Middleware Token - Token vazio.');
        } else if (!str_starts_with($token, 'Bearer ')) {
            $this->erroToken('Middleware Token - Token não começa com Bearer.');
        }
        $this->token = preg_replace('/^Bearer /', '', $this->token);

        if (mb_strlen($this->token) != 36 && !(new JwtHelper())->validar($this->token)) {
            $this->erroToken('Middleware Token - Não foi possível validar token.');
        }
    }

    private function pegarBody()
    {
        if (mb_strlen($this->token) == 36) {
            return;
        }
        try {
            $Jwt = new JwtHelper();
            $this->body = $Jwt->decode($this->token);
        } catch (\Throwable $e) {
            $this->erroToken('Middleware Token - Erro ao pegar body do token - ' . $e->getMessage() . '.');
        }
    }

    private function pegarTipoDeToken()
    {
        if (mb_strlen($this->token) == 36) {
            return 'authorization';
        } else if (array_key_exists('gty', $this->body) && $this->body['gty'] == 'client-credentials') {
            return 'client-credentials';
        }
        $this->erroToken('Middleware Token - Token não tem 36 caracteres ou é um JWT.');
    }

    private function erroToken($mensagem)
    {
        mensagemErro('Token inválido!', 'Envie um token válido para autenticação.', 401, localhost: $mensagem);
    }
}
