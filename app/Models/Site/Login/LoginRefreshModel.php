<?php

namespace App\Models\Site\Login;

use Helpers\CurlHelper;
use App\Models\Site\Link\ApiModel;

final class LoginRefreshModel
{
    private array $token;

    public function __construct(
        private string $refreshToken
    ) {
        if (empty($refreshToken)) {
            mensagemStatus(401);
        }
        $this->gerarRefreshToken();
        new AuthModel($this->token, true);
    }

    private function gerarRefreshToken()
    {
        $linkProd = (new ApiModel())->link;
        $dado = (new CurlHelper($linkProd))
            ->body([
                'grant_type'    => 'refresh_token',
                'client_id'     => env('API_REFRESH_CLIENT_ID'),
                'secret_id'     => env('API_REFRESH_SECRET_ID'),
                'refresh_token' => $this->refreshToken,
                'scope'         => ''
            ])
            ->post('/token')
            ->array();
        if (
            !is_array($dado) ||
            !array_key_exists('dado', $dado) ||
            !array_key_exists('token', $dado['dado'])) {
            mensagemStatus(401);
        }
        $this->token = $dado['dado']['token'];
    }
}
