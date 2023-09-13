<?php

namespace App\Models\Site\Login;

use Helpers\ApiHelper;

final class LoginApiModel
{
    private array $token;

    public function __construct(private string $hash)
    {
        $this->validarDado();
        $this->fazerLogin();
        new AuthModel($this->token);
    }

    private function validarDado()
    {
        if (empty($this->hash)) {
            mensagemStatus(401);
        }
    }

    private function fazerLogin()
    {
        $dado = (new ApiHelper(scope: 'login:clube'))
            ->validar(status: 401)
            ->body([
                'hash'         => $this->hash,
                'redirect_uri' => LINK,
                'scope'        => '',
                'state'        => uuid(),
            ])
            ->post('/login/hash')->array()['dado'];

        $this->token = $dado['token'];
    }
}
