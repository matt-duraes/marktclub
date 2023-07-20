<?php

namespace App\Models\Api\LoginClube;

use stdClass;
use Modules\Senha;
use App\Classes\UsuarioCliente\TipoUsuario;
use App\Classes\LoginClube\PegarClienteTrait;

final class LoginMarktClubModel extends LoginPadraoModel
{
    use PegarClienteTrait;

    public stdClass $Usuario;

    public function __construct(
        private string $login,
        private string $senha,
        private int $empresa
    ) {
        $this->validarDadosDeLogin();
        $this->buscarUsuarioPeloLoginSenha();
    }

    protected function validarDadosDeLogin(): void
    {
        if (empty($this->login)) {
            mensagemErro(titulo: 'Campo obrigatório!', mensagem: 'Você deve digitar seu login para continuar.');
        } elseif (empty($this->senha)) {
            mensagemErro(titulo: 'Campo obrigatório!', mensagem: 'Você deve digitar sua senha para continuar.');
        }
    }

    protected function buscarUsuarioPeloLoginSenha(): void
    {
        $Usuario = $this->pegarCliente($this->pegarWhere());
        if (vazio($Usuario)) {
            password_verify($this->senha, '$2y$11$gqvgsZOatns5gStLVwaz8uANvVsSvSvq4WS8OH5lz2tJaXcO1h23O');
            $this->UsuarioNaoEncontrado();
        }

        $Senha = new Senha($Usuario->salt);
        if (!$Senha->validarSenha($this->senha)) {
            $this->UsuarioNaoEncontrado();
        }

        $this->Usuario = $Usuario;
    }

    protected function pegarWhere(): array
    {
        $where = [[
            'OR',
            ['empresa', $this->empresa],
            [
                ['empresa', 1],
                ['tipo', (new TipoUsuario())->numero(TipoUsuario::SUPER)]
            ]
        ]];

        $cpf = soNumero($this->login);
        if (!empty($cpf) && validarCpf($cpf)) {
            return array_merge([['documento', $cpf]], $where);
        }

        $email = $this->login;
        return array_merge([
            'OR',
            ['email_pessoal', $email],
            ['email_trabalho', $email],
        ], $where);
    }
}
