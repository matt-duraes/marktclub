<?php

namespace App\Models\Site\Webview;

use Helpers\ApiHelper;
use Helpers\AuthHelper;
use Helpers\CryptHelper;
use App\Models\Site\Login\CryptTrait;

final class LoginModel
{
    use CryptTrait;

    public string $link;
    private string $token;
    private string $idUsuario;
    private array $usuario;
    private CryptHelper $Crypt;

    public function __construct(
        private LocalInterface $Local
    ) {
        $this->link = $Local->link();
        $this->pegarToken();
        $this->validarToken();
        $this->setarCrypt($this->token);
        $this->pegarIdUsuario();
        $this->buscarUsuario();
        $this->setarLink();
        (new AuthHelper())->criar($this->usuario);
        sessao('TOKEN', $this->token);
        sessao('TOKEN_EXPIRE', date('Y-m-d H:i:s', time() + 50000));
        cookie('CLT', base64Encode(
            [
                'token' => '',
                'data'  => agora()
            ],
            true
        ));
    }

    private function pegarToken()
    {
        $header = getallheaders();
        $this->token = $header['Authorization'] ?? $header['authorization'] ?? $Authorization['AUTHORIZATION'] ?? '';
    }

    private function validarToken()
    {
        if (empty($this->token) || !preg_match('/^Bearer [a-z0-9\-]{36}/i', $this->token)) {
            mensagemStatus(404);
        }
        $this->token = preg_replace('/^Bearer /i', '', $this->token);
    }

    private function pegarIdUsuario()
    {
        $header = getallheaders();
        $this->idUsuario = $header['usuario'] ?? $header['USUARIO'] ?? '';
    }

    private function buscarUsuario()
    {
        $Api = new ApiHelper(token: $this->token);
        $dado = $Api
            ->validar(status: 404)
            ->get('/usuario-cliente/' . $this->idUsuario)
            ->array()['dado'];

        $this->usuario = [
            'id'              => $dado['id'],
            'nome'            => $this->Crypt->decode($dado['nome']),
            'cpf'             => $this->Crypt->decode($dado['cpf']),
            'email'           => $this->Crypt->decode($dado['email_pessoal']),
            'imagem'          => $this->Crypt->decode($dado['imagem']),
            'tipo'            => '',
            'federacao'       => '',
            'grupo'           => '',
            'novo'            => '',
            'atualizar_senha' => '',
            'lgpd'            => 'sim',
        ];
    }

    private function setarLink()
    {
        $link = $this->Local->link();
        $parametro = $_SERVER['QUERY_STRING'] ?? '';
        if (empty($parametro)) {
            $this->link = $link;
            return;
        } elseif (str_contains($link, '?')) {
            $this->link = $link . '&' . $parametro;
            return;
        }
        $this->link = $link . '?' . $parametro;
    }
}
