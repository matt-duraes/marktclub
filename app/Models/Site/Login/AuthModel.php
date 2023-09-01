<?php

namespace App\Models\Site\Login;

use Helpers\JwtHelper;
use Helpers\AuthHelper;
use Helpers\CryptHelper;

final class AuthModel
{
    use CryptTrait;

    private CryptHelper $Crypt;

    public function __construct($token, $clube, bool $refresh = false)
    {
        $this->setarCrypt($refresh ? $token['access_token'] : null);
        $usuario = $this->pegarUsuario($token);
        (new AuthHelper())->criar($usuario);

        sessao('TOKEN', $token['access_token']);
        sessao('TOKEN_EXPIRE', date('Y-m-d H:i:s', time() + $token['expires_in'] - 60));
        cookie('CLT', base64Encode(
            [
                'token' => $token['refresh_token'],
                'data'  => agora()
            ],
            true
        ));
    }

    private function pegarUsuario(array $token): array
    {
        $dado = (new JwtHelper())->decode($token['id_token']);
        return [
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
}
