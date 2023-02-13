<?php

namespace PainelApp\login\Models;

use stdClass;
use Helpers\JwtHelper;
use Helpers\AuthHelper;
use Helpers\CryptHelper;
use PainelApp\login\Models\Trait\ChaveTrait;

final class AutenticarUsuarioModel
{
    use ChaveTrait;

    private stdClass $token;
    private array $body;

    public function __construct(
        private LoginInterface $Login
    ) {
        $this->token = $Login->pegarToken()->dado;

        $this->setarChaves();
        $this->pegarBodyDoToken();
        $this->autenticarUsuario();
    }

    private function pegarBodyDoToken()
    {
        $Jwt = new JwtHelper();
        $body = $Jwt->decode($this->token->id_token);
        $Crypt = new CryptHelper(chavePrivada: $this->chavePrivada);
        $this->body = [
            'id' => $body['sub'],
            'nome' => $Crypt->decode($body['name']),
            'email' => $Crypt->decode($body['email']),
            'imagem' => $Crypt->decode($body['picture']),
        ];
    }
    public function autenticarUsuario()
    {
        (new AuthHelper)->criar($this->body);

        sessao('TOKEN', $this->token->access_token);
        sessao('TOKEN_EXPIRE', date('Y-m-d H:i:s', time() + $this->token->expires_in - 60));
        cookie('FWT', base64Encode(
            [
                'token' => $this->token->refresh_token,
                'data' => agora()
            ],
            'hash_refresh_token'
        ));
    }
}
