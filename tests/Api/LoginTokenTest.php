<?php

namespace Tests\Api;

use Tests\Tests;

final class LoginTokenTest extends Tests
{
    public function fazerLoginCorretoTest()
    {
        $this->api('login:token');
        $this
            ->Curl
            ->body([
                'usuario' => '5595203c-f7b1-4211-9981-bf09eb236b35',
                'clube' => 'b7ecc8af-25c1-4981-a891-cc60c3464f6c'
            ])
            ->post('/login/token');

        return $this
            ->checkStatus(201)
            ->checkIndiceExiste('dado.access_token');
    }
    public function naoPodeFazerLoginComUsuarioVazioTest()
    {
        $this->api('login:token');
        $this
            ->Curl
            ->body([
                'usuario' => '',
                'clube' => 'b7ecc8af-25c1-4981-a891-cc60c3464f6c'
            ])
            ->post('/login/token');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', 'Você deve passar um usuário para continuar.');
    }
    public function naoPodeFazerLoginComClubeVazioTest()
    {
        $this->api('login:token');
        $this
            ->Curl
            ->body([
                'usuario' => '5595203c-f7b1-4211-9981-bf09eb236b35',
                'clube' => ''
            ])
            ->post('/login/token');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', 'Você deve passar um clube para continuar.');
    }
    public function naoPodeFazerLoginComUsuarioInvalidoTest()
    {
        $this->api('login:token');
        $this
            ->Curl
            ->body([
                'usuario' => '123',
                'clube' => 'b7ecc8af-25c1-4981-a891-cc60c3464f6c'
            ])
            ->post('/login/token');

        return $this
            ->checkStatus(404)
            ->checkIndiceIgual('erro.mensagem', 'Usuário não encontrado.');
    }
    public function naoPodeFazerLoginComClubeInvalidoTest()
    {
        $this->api('login:token');
        $this
            ->Curl
            ->body([
                'usuario' => '5595203c-f7b1-4211-9981-bf09eb236b35',
                'clube' => '123'
            ])
            ->post('/login/token');

        return $this
            ->checkStatus(404)
            ->checkIndiceIgual('erro.mensagem', 'Clube não encontrado.');
    }
}
