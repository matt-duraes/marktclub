<?php

namespace Tests\Api\Token;

use Tests\Tests;
use Helpers\ApiHelper;
use Helpers\CryptHelper;

abstract class Clube extends Tests
{
    public function pegarToken()
    {
        $Curl = new ApiHelper('admin:chave_publica');
        $chave = $Curl->get('/admin/chave-publica')->object()->dado->chave ?? '';

        $Crypt = new CryptHelper(chavePublica: $chave);

        $login = env('TESTS_PAINEL_LOGIN', '01234567890');
        $senha = env('TESTS_PAINEL_SENHA', 'Teste@1324');

        $token = (new ApiHelper(scope: 'login:clube'))
            ->body([
                'login'        => $Crypt->encode($login),
                'senha'        => $Crypt->encode($senha),
                'redirect_uri' => env('API_REDIRECT_URI', ''),
                'scope'        => '',
                'state'        => uuid()
            ])
            ->post('/login/clube')
            ->array()['dado']['token']['access_token'] ?? '';

        return 'Bearer ' . $token;
    }
}
