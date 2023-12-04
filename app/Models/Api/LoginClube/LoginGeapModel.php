<?php

namespace App\Models\Api\LoginClube;

use stdClass;
use App\Helpers\Geap\TokenHelper;
use App\Classes\LoginClube\PegarClienteTrait;

final class LoginGeapModel extends LoginPadraoModel
{
    use PegarClienteTrait;

    public stdClass $Usuario;

    public function __construct(
        private readonly ?string $login = null,
        private readonly ?string $senha = null,
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
        $Token = new TokenHelper(
            login: $this->login,
            senha: $this->senha
        );
    }

    protected function pegarWhere(): array
    {
        return [];
    }
}
