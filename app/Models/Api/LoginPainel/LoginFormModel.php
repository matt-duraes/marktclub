<?php

namespace App\Models\Api\LoginPainel;

use Http\Request;
use App\Models\Api\UsuarioEquipe\EquipeEntity;

final class LoginFormModel
{
    private EquipeEntity $Usuario;

    /**
     * Faz o login normal do usuário com usuario e senha
     *
     * @param Request $request Request da requisição
     */
    public function __construct(
        private string $login,
        private string $senha
    ) {
        $this->validarDadosDeLogin();
        $this->buscarUsuarioPeloLoginSenha();
    }

    public function pegarUsuario(): EquipeEntity
    {
        return $this->Usuario;
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS PRIVADOS DO LOGIN
    |--------------------------------------------------------------------------
    */
    private function validarDadosDeLogin(): void
    {
        $login = preg_replace('/[^0-9]/', '', $this->login);
        if (empty($this->login)) {
            mensagemErro(titulo: 'Campo obrigatório!', mensagem: 'Você deve digitar seu login para continuar.');
        } elseif (empty($login)) {
            mensagemErro(titulo: 'Campo inválido!', mensagem: 'Seu login deve ser um CPF válido.');
        } elseif (empty($this->senha)) {
            mensagemErro(titulo: 'Campo obrigatório!', mensagem: 'Você deve digitar sua senha para continuar.');
        }
    }

    private function buscarUsuarioPeloLoginSenha(): void
    {
        $documento = preg_replace('/[^0-9]/', '', $this->login);
        $Usuario = new EquipeEntity(validarToken: false);
        try {
            $Usuario->buscar(where: [
                ['documento_cpf', $documento],
                ['status', 1]
            ]);
        } catch (\Throwable) {
            password_verify($this->senha, '$2y$11$gqvgsZOatns5gStLVwaz8uANvVsSvSvq4WS8OH5lz2tJaXcO1h23O');
            $this->UsuarioNaoEncontrado();
        }

        if (!$Usuario->senha->validarSenha($this->senha)) {
            $this->UsuarioNaoEncontrado();
        }

        $this->Usuario = $Usuario;
    }

    private function usuarioNaoEncontrado(): void
    {
        mensagemErro(
            titulo: 'Dados inválidos',
            mensagem: 'O seu login e/ou senha estão incorretos, verifique os dados informados e tente novamente.',
            status: 400
        );
    }
}
