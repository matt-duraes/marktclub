<?php

namespace App\Models\Api\LoginClube;

abstract class LoginPadraoModel
{
    abstract protected function validarDadosDeLogin(): void;

    abstract protected function buscarUsuarioPeloLoginSenha(): void;

    protected function usuarioNaoEncontrado(): void
    {
        mensagemErro(
            titulo: 'Dados inválidos',
            mensagem: 'O seu login e/ou senha estão incorretos, verifique os dados informados e tente novamente.',
            status: 400
        );
    }
}
