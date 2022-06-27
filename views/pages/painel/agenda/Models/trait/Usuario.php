<?php

namespace Painel\Agenda\Models\Trait;

trait Usuario
{
    private function pegarNomePeloEmail($email)
    {
        return $email;
    }

    private function pegarImagemPeloEmail($email)
    {
        if ($this->meuEmail() == $email) {
            return $this->minhaImagem();
        }
        return LINK_PADRAO . '/images/painel/usuario_padrao_preto.png';
    }

    private function meuNome()
    {
        return sessao('USUARIO.nome');
    }
    private function meuEmail()
    {
        return sessao('USUARIO.email');
    }

    private function minhaImagem()
    {
        return sessao('USUARIO.imagem');
    }
}
