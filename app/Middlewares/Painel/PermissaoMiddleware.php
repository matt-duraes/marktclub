<?php

namespace App\Middlewares\Painel;

final class PermissaoMiddleware
{
    public function validar($permissao)
    {
        $permissaoPainel = sessao('PAINEL.permissao.lista', padrao: []);
        $permissaoUsuario = sessao('USUARIO.permissao', padrao: []);

        if (in_array($permissao, $permissaoUsuario) && in_array($permissao, $permissaoPainel)) {
            return true;
        }
        return mensagemStatus(403);
    }
}
