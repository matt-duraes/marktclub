<?php


namespace Tests\Api;

use Tests\Tests;

final class LoginPainelTest extends Tests
{
    public function fazerLoginComLoginSenhaCorretosTest()
    {
        $this->api('login:painel');
        $this
            ->Curl
            ->body($this->pegarBody('01234567890', 'Teste@1324'))
            ->post('/login/painel');

        return $this
            ->checkStatus(201)
            ->checkIndiceExiste('dado.access_token');
    }

    public function naoPodeFazerLoginSemLoginTest()
    {
        $this->api('login:painel');
        $this
            ->Curl
            ->body($this->pegarBody('', '123456'))
            ->post('/login/painel');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', 'Você deve digitar seu login para continuar.')
            ->checkIndiceNaoExiste('dado.access_token');
    }

    public function naoPodeFazerLoginSemSenhaTest()
    {
        $this->api('login:painel');
        $this
            ->Curl
            ->body($this->pegarBody('01495180131', ''))
            ->post('/login/painel');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', 'Você deve digitar sua senha para continuar.')
            ->checkIndiceNaoExiste('dado.access_token');
    }

    public function naoPodeFazerLoginComLoginErradoTest()
    {
        $this->api('login:painel');
        $this
            ->Curl
            ->body($this->pegarBody('01234567890', '123456'))
            ->post('/login/painel');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', 'O seu login e/ou senha estão incorretos, verifique os dados informados e tente novamente.')
            ->checkIndiceNaoExiste('dado.access_token');
    }

    public function naoPodeFazerLoginComSenhaErradaTest()
    {
        $this->api('login:painel');
        $this
            ->Curl
            ->body($this->pegarBody('01495180131', 'sem_senha'))
            ->post('/login/painel');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', 'O seu login e/ou senha estão incorretos, verifique os dados informados e tente novamente.')
            ->checkIndiceNaoExiste('dado.access_token');
    }

    /*
    |--------------------------------------------------------------------------
    | PRIVADOS
    |--------------------------------------------------------------------------
    */
    private function pegarBody(string $login, string $senha)
    {
        return [
            'login' => $this->cryptEncode($login),
            'senha' => $this->cryptEncode($senha),
            'facebook' => '',
            'google' => '',
            'scope' => '',
            'audience' => env('API_AUDIENCE'),
            'redirect_uri' => env('API_REDIRECT_URI'),
            'state' => uuid()
        ];
    }
}
