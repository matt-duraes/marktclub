<?php

namespace App\Helpers\Geap;

use Helpers\CurlHelper;

final class TokenHelper extends CurlHelper
{
    public string $token;
    private string $username;
    private string $password;

    public function __construct()
    {
        parent::__construct(env('GEAP_TOKEN_LINK', ''));
        $this->username = env('GEAP_TOKEN_USERNAME', '');
        $this->password = env('GEAP_TOKEN_PASSWORD', '');
        $this->token();
    }

    private function token()
    {
        $token = $this
            ->header([
                'Authorization' => 'Basic ' . base64_encode($this->username . ':' . $this->password)
            ])
            ->body([
                'grant_type' => 'client_credentials'
            ])
            ->post('/connect/token')
            ->object();

        if (!object_key_exists('access_token', $token)) {
            mensagemErro(
                'Erro!',
                'Ocorreu um erro ao fazer seu login, por favor, tente novamente.',
                status: 401,
                localhost: 'Não foi possível criar o token - ' . $token->error ?? ''
            );
        }
        $this->token = $token->access_token;
    }
}
