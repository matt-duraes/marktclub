<?php

namespace App\Models\Api\Samsung;

use ORM\ORM;
use Helpers\JwtHelper;

final class ValidarModel extends ORM
{
    protected string $ormTabela = TABELA_USUARIO_CLIENTE;
    private array $usuario = [];
    private string $email = '';
    private string $tokenDecript;
    public string $userName;
    public string $password;
    public string $token;
    public string $auth;

    public function __construct(string $code)
    {
        parent::__construct();

        $this->userName = env('SAMSUNG_USER_NAME', '');
        $this->password = env('SAMSUNG_PASSWORD', '');
        $this->token = env('SAMSUNG_TOKEN', '');
        $this->tokenDecript = env('SAMSUNG_TOKEN_DECRIPT', '');
        $this->auth = env('SAMSUNG_AUTH', '');

        $this->descriptografarCode($code);
        $this->buscarUsuario();
    }

    private function descriptografarCode(string $code)
    {
        $Jwt = new JwtHelper(hash: $this->tokenDecript);
        try {
            $dado = $Jwt->decode($code);
        } catch (\Throwable) {
            return;
        }
        if (!is_array($dado) || !array_key_exists('email', $dado)) {
            return;
        }
        $this->email = $dado['email'];
    }

    private function buscarUsuario()
    {
        if (empty($this->email)) {
            return;
        }
        $usuario = $this
            ->campo(['nome', 'status'])
            ->where([
                [
                    'OR',
                    ['email_pessoal', $this->email],
                    ['email_trabalho', $this->email],
                ],
                ['status', 'in', [1, 2]]
            ])
            ->primeiro();
        if (!$usuario) {
            return;
        }
        $this->usuario = (array)$usuario;
    }

    private function semPermissao()
    {
        return [
            'userExistis' => false,
            'userActive'  => false,
            'urlRedirect' => null,
            'partner'     => 'Partner Markt Club',
            'message'     => 'Usuário não está cadastrado em nossa base.'
        ];
    }

    private function sucesso()
    {
        return [
            'userExistis' => true,
            'userActive'  => true,
            'token'       => $this->json()
        ];
    }

    private function json()
    {
        $Jwt = new JwtHelper(hash: $this->tokenDecript);
        try {
            return $Jwt->encode([
                'email'      => $this->email,
                'fullName'   => $this->usuario['nome'],
                'expireUser' => dataAdicionar(hoje(), 10, 'dias'),
                'utmCode'    => ''
            ]);
        } catch (\Throwable) {
            return '';
        }
    }

    public function validar()
    {
        if (
            empty($this->usuario) ||
            !array_key_exists('status', $this->usuario) ||
            !in_array($this->usuario['status'], [1, 2])
        ) {
            return $this->semPermissao();
        }
        return $this->sucesso();
    }

    public function pegarHeader()
    {
        $Jwt = new JwtHelper(hash: $this->tokenDecript);
        try {
            return $Jwt->encode([
                'user_name_partner'     => $this->userName,
                'user_password_partner' => $this->password,
                'user_token_partner'    => $this->token,
            ]);
        } catch (\Throwable) {
            return '';
        }
    }
}
