<?php

if (!function_exists('temPermissaoEmpresa')) {
    function temPermissaoEmpresa(string $app): bool
    {
        $usuarioPermissao = sessao('USUARIO.permissao');
        return in_array($app . '_empresa', $usuarioPermissao) && EMPRESA_ID == '14afa776394ada4be23be6acf7e3259e';
    }
}

if (!function_exists('temPermissao')) {
    function temPermissao(string $permissao): bool
    {
        $usuarioPermissao = sessao('USUARIO.permissao');
        return in_array($permissao, $usuarioPermissao);
    }
}
