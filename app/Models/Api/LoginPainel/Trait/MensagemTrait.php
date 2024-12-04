<?php

namespace App\Models\Api\LoginPainel\Trait;

trait MensagemTrait
{
    private function erroLogin($mensagem)
    {
        mensagemErro(
            titulo: 'Erro ao fazer login!',
            mensagem: 'Ocorreu um erro ao fazer login, por favor, tente novamente.',
            localhost: $mensagem
        );
    }
}
