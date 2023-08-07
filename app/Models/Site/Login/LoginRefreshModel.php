<?php

namespace App\Models\Site\Login;

use Helpers\CurlHelper;

final class LoginRefreshModel
{
    private array $token;
    private array $clube;

    public function __construct(
        private string $refreshToken
    ) {
        if (empty($refreshToken)) {
            mensagemStatus(401);
        }
        $this->gerarRefreshToken();
        new AuthModel($this->token, $this->clube, true);
    }

    private function gerarRefreshToken()
    {
        $dado = (new CurlHelper(env('API_LINK', LINK_API)))
            ->body([
                'grant_type'    => 'refresh_token',
                'client_id'     => env('API_CLIENT_ID'),
                'secret_id'     => env('API_SECRET_ID'),
                'refresh_token' => $this->refreshToken,
                'scope'         => ''
            ])
            ->post('/token')
            ->array();
        if (
            !is_array($dado) ||
            !array_key_exists('dado', $dado) ||
            !array_key_exists('token', $dado['dado']) ||
            !array_key_exists('clube', $dado['dado'])) {
            mensagemStatus(401);
        }
        $this->token = $dado['dado']['token'];
        $this->clube = $dado['dado']['clube'];
    }
}
