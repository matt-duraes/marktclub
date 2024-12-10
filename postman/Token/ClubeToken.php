<?php

namespace Postman\Token;

use System\Html\Postman\Models\TokenPadrao;

final class ClubeToken extends TokenPadrao
{
    public function __construct()
    {
        parent::__construct('clube', 'Login Clube', 'login:clube');
    }

    public function retornarToken(): string
    {
        $header = $this->headerAuthorization();
        $token = $this->enviarCurl(
            metodo: 'POST',
            uri: '{{LINK}}/login/clube',
            body: [
                'login'        => $this->encode(env('POSTMAN_LOGIN')),
                'senha'        => $this->encode(env('POSTMAN_SENHA')),
                'scope'        => '',
                'redirect_uri' => env('POSTMAN_API_REDIRECT_URI'),
                'state'        => uuid(),
                'tipo'         => 'titular'
            ],
            header: $header
        );
        $token = jsonDecode($token->retorno, true, true);
        if (!validarIndiceExiste($token, ['dado.token.access_token', 'status' => 'sucesso'])) {
            return '';
        }
        return $token['dado']['token']['access_token'];
    }
}
