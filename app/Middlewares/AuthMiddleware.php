<?php

namespace App\Middlewares;

use Erro\Excecao;
use Http\Response;
use Helpers\AuthHelper;

final class AuthMiddleware
{
    public function logado($class = null, ?string $action = null)
    {
        $retorno = $this->verificarSeEstaLogado();
        if (
            (is_bool($retorno) && !$retorno) ||
            (
                !is_bool($retorno) &&
                !empty($class) &&
                !empty($action) &&
                !call_user_func_array([new $class(), $action], [$retorno])
            )
        ) {
            return $this->retornoUsuarioNaoLogado();
        }
        return true;
    }

    public function deslogado()
    {
        return true;
    }

    private function verificarSeEstaLogado()
    {
        $logado = (new AuthHelper())->validar();
        if (true === $logado) {
            return true;
        }

        $nome = hashIpUser('auth');
        if (!array_key_exists($nome, $_COOKIE) || empty($_COOKIE[$nome])) {
            return false;
        }
        return $_COOKIE[$nome];
    }

    private function retornoUsuarioNaoLogado()
    {
        if (METODO == 'GET' && CONTENT_TYPE != 'application/json') {
            return new Response(url: LINK . '/login');
        }
        throw new Excecao(status: 401);
    }
}
