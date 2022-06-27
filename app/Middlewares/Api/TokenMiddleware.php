<?php

namespace App\Middlewares\Api;

use Helpers\JwtHelper;
use App\Models\Api\ApiToken\ValidarTokenCredentialModel;
use App\Models\Api\ApiToken\ValidarTokenAuthorizationEntity;

final class TokenMiddleware
{
    private string $token;
    private array $body = [];

    public function __construct()
    {
        $header = getallheaders();
        $this->token = $header['Authorization'] ??
            $header['authorization'] ??
            $_SERVER['HTTP_AUTHORIZATION'] ??
            false;
    }

    public function token()
    {
        $this->validarTokenEnviado();
        $this->pegarBody();
        $tipo = $this->pegarTipoDeToken();
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
            } catch (\Throwable) {
                mensagemStatus(403, localhost: 'Middleware Token - Não foi possível achar seu token ou o status dele não é 1');
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
        return true;
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
    }

    private function pegarBody()
    {
        if (mb_strlen($this->token) == 36) {
            return;
        }
        try {
            $Jwt = new JwtHelper($this->token);
            $this->body = $Jwt->body();
        } catch (\Throwable) {
            $this->erroToken('Middleware Token - Erro ao pegar body do token.');
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
        mensagemErro('Token inválido!', 'Enviei um token válido para autenticação.', 401, localhost: $mensagem);
    }
}
