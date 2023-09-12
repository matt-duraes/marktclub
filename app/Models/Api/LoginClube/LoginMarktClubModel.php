<?php

namespace App\Models\Api\LoginClube;

use stdClass;
use Modules\Senha;
use App\Classes\UsuarioCliente\Hash;
use App\Classes\UsuarioCliente\TipoUsuario;
use App\Classes\LoginClube\PegarClienteTrait;

final class LoginMarktClubModel extends LoginPadraoModel
{
    use PegarClienteTrait;

    public stdClass $Usuario;

    public function __construct(
        private ?string $login = null,
        private ?string $senha = null,
        private ?string $hash = null,
        private ?int $empresa = null
    ) {
        $this->validarDadosDeLogin();
        $this->buscarUsuarioPeloLoginSenha();
    }

    protected function validarDadosDeLogin(): void
    {
        if (!empty($this->hash)) {
            return;
        } elseif (empty($this->login)) {
            mensagemErro(titulo: 'Campo obrigatório!', mensagem: 'Você deve digitar seu login para continuar.');
        } elseif (empty($this->senha)) {
            mensagemErro(titulo: 'Campo obrigatório!', mensagem: 'Você deve digitar sua senha para continuar.');
        }
    }

    protected function buscarUsuarioPeloLoginSenha(): void
    {
        $Usuario = $this->pegarCliente($this->pegarWhere());
        if (vazio($Usuario)) {
            if (empty($this->hash)) {
                password_verify($this->senha, '$2y$11$gqvgsZOatns5gStLVwaz8uANvVsSvSvq4WS8OH5lz2tJaXcO1h23O');
            }
            $this->UsuarioNaoEncontrado();
        }

        if (empty($this->hash)) {
            $Senha = new Senha($Usuario->salt);
            if (!$Senha->validarSenha($this->senha)) {
                $this->UsuarioNaoEncontrado();
            }
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

        if (!empty($this->hash)) {
            return array_merge([
                ['hash', $this->hash],
                ['hash_tipo', Hash::LOGIN],
                ['hash_data', '<', dataAdicionar(agora(), 10, 'minutos', 'Y-m-d H:i:s')]
            ], $where);
        }

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
