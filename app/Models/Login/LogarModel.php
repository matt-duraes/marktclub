<?php

namespace App\Models\Login;

use Helpers\ApiHelper;
use Helpers\JwtHelper;
use Helpers\AuthHelper;
use Helpers\CryptHelper;

final class LogarModel
{
    private CryptHelper $Crypt;
    private array $token;
    private array $clube;
    private array $usuario;

    public function __construct(
        private string $login,
        private string $senha
    ) {
        $this->validarDado();
        $this->setarCrypt();
        $this->fazerLogin();
        $this->setarUsuario();
        $this->criarAuth();
    }

    private function validarDado()
    {
        if (empty($this->login)) {
            mensagemErro('Campo obrigatório!', 'Digite seu login para continuar.');
        } elseif (empty($this->senha)) {
            mensagemErro('Campo obrigatório!', 'Digite sua senha para continuar.');
        }
    }

    private function setarCrypt()
    {
        $publica = (new ApiHelper('admin:chave_publica'))->get('/admin/chave-publica')->object()->dado->chave ?? '';
        $privada = (new ApiHelper('admin:chave_privada'))->get('/admin/chave-privada')->object()->dado->chave ?? '';
        $this->Crypt = new CryptHelper(chavePublica: $publica, chavePrivada: $privada);
    }

    private function fazerLogin()
    {
        $dado = (new ApiHelper(scope: 'login:clube'))
            ->validar('Erro ao fazer o login, por favor, tente novamente.')
            ->body([
                'login'        => $this->Crypt->encode($this->login),
                'senha'        => $this->Crypt->encode($this->senha),
                'redirect_uri' => env('API_REDIRECT_URI'),
                'scope'        => '',
                'state'        => uuid(),
            ])
            ->post('/login/clube')->array()['dado'];
        $this->token = $dado['token'];
        $this->clube = $dado['clube'];
    }

    private function setarUsuario()
    {
        $dado = (new JwtHelper())->decode($this->token['id_token']);
        $this->usuario = [
            'id'              => $dado['sub'],
            'nome'            => $this->Crypt->decode($dado['name']),
            'cpf'             => $this->Crypt->decode($dado['document']),
            'email'           => $this->Crypt->decode($dado['email']),
            'imagem'          => $this->Crypt->decode($dado['picture']),
            'tipo'            => $dado['type'],
            'grupo'           => $dado['group'],
            'novo'            => $dado['new_user'],
            'atualizar_senha' => $dado['update_password'],
            'lgpd'            => $dado['lgpd'],
        ];
    }

    private function criarAuth()
    {
        (new AuthHelper())->criar($this->usuario, 'SITE');

        sessao('CLUBE', $this->clube);
        sessao('TOKEN', $this->token['access_token']);
        sessao('TOKEN_EXPIRE', date('Y-m-d H:i:s', time() + $this->token['expires_in'] - 60));
        cookie('CLT', base64Encode(
            [
                'token' => $this->token['refresh_token'],
                'data'  => agora()
            ],
            true
        ));
    }
}
