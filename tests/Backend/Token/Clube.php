<?php

namespace Tests\Token;

use Tests\Tests;
use Erro\Excecao;
use Helpers\ApiHelper;
use Helpers\CryptHelper;
use App\Classes\LoginClube\Tipo;

abstract class Clube extends Tests
{
    /**
     * @return string
     * @throws Excecao
     */
    public function pegarToken(string $login = null, string $senha = null): void
    {
        $Curl = new ApiHelper('admin:chave_publica');
        $chave = $Curl
            ->get('/admin/chave-publica')
            ->object()->dado->chave ?? '';

        $Crypt = new CryptHelper(chavePublica: $chave);

        $login = !empty($login) ? $login : env('TESTS_PAINEL_LOGIN', '01234567890');
        $senha = !empty($senha) ? $senha : env('TESTS_PAINEL_SENHA', 'Teste@1324');

        $token = (new ApiHelper(scope: 'login:clube'))
            ->body([
                'login'        => $Crypt->encode($login),
                'senha'        => $Crypt->encode($senha),
                'redirect_uri' => env('API_REDIRECT_URI', ''),
                'scope'        => '',
                'state'        => uuid(),
                'tipo'         => Tipo::TITULAR
            ])
            ->post('/login/clube')
            ->array()['dado']['token']['access_token'] ?? '';

        if (empty($token)) {
            mensagemErro('Erro', 'Erro ao fazer login no clube para realizar os testes.');
        }

        $this->checkCurl = true;
        $this->checkRobo = false;
        $this->Curl = new ApiHelper(token: $token);
    }
}
