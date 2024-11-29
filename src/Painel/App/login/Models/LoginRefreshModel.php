<?php

namespace PainelApp\login\Models;

use Helpers\ApiHelper;
use PainelApp\login\Models\Trait\TokenTrait;

final class LoginRefreshModel implements LoginInterface
{
    use TokenTrait;

    private array $body;

    public function __construct(
        private string $refreshToken,
    ) {
        $this->montarBodyDaRequisicao();
        $this->fazerRequisicao();
    }

    private function montarBodyDaRequisicao()
    {
        $this->body = [
            'grant_type'    => 'refresh_token',
            'client_id'     => env('API_REFRESH_CLIENT_ID', ''),
            'secret_id'     => env('API_REFRESH_SECRET_ID', ''),
            'refresh_token' => $this->refreshToken,
            'scope'         => ''
        ];
    }

    private function fazerRequisicao()
    {
        $token = (new ApiHelper(scope: ''))
            ->validar('Erro criar novo token.')
            ->body($this->body)
            ->post('/token')
            ->object();
        $this->token = $token;
    }
}
