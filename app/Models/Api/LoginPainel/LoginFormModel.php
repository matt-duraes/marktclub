<?php

namespace App\Models\Api\LoginPainel;

use stdClass;
use Http\Request;
use Modules\Senha;
use App\Classes\LoginPainel\PegarEquipeTrait;

final class LoginFormModel
{
    use PegarEquipeTrait;

    private stdClass $Usuario;

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

    public function pegarUsuario(): stdClass
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
        $Usuario = $this->pegarEquipe([
            ['documento_cpf', $documento],
            ['status', 1]
        ]);

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

    private function usuarioNaoEncontrado(): void
    {
        mensagemErro(
            titulo: 'Dados inválidos',
            mensagem: 'O seu login e/ou senha estão incorretos, verifique os dados informados e tente novamente.',
            status: 400
        );
    }
}
