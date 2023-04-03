<?php

namespace System\System;

use Http\Response;
use Route\Config as RouteConfig;

final class Middleware
{
    private array $middlewarePre = [];
    private array $middlewarePos = [];

    public function __construct(
        private RouteConfig $route
    ) {
        $rota = $this->route->rotaUso();
        if (!array_key_exists('middleware', $rota)) {
            return;
        }
        $this->middlewarePre = $rota['middleware']['pre'];
        $this->middlewarePos = $rota['middleware']['pos'];
        $this->verificarSeMiddlewareExiste();
    }

    private function verificarSeMiddlewareExiste(): void
    {
        $middleware = array_merge($this->middlewarePre, $this->middlewarePos);
        foreach ($middleware as $r) {
            $namespace = $r['classe'];
            $action = $r['action'];
            if (!class_exists($namespace) || !method_exists($namespace, $action)) {
                mensagemErro(
                    titulo: 'Middleware não encontrado!',
                    mensagem: 'Não foi possível encontrar o midleware '
                        . $namespace . ' ou seu action ' . $action . '.',
                    status: 500
                );
            }
        }
    }

    public function pre(): bool | Response
    {
        return $this->executarLista($this->middlewarePre);
    }

    public function pos(): bool | Response
    {
        return $this->executarLista($this->middlewarePos);
    }

    private function executarLista($middleware): bool | Response
    {
        if (!$middleware) {
            return true;
        }
        foreach ($middleware as $r) {
            $classe = $r['classe'];
            $action = $r['action'];
            $construtor = $r['construtor'];
            $parametro = $r['parametro'];

            $app = new $classe($construtor);
            $retorno = call_user_func_array([$app, $action], $parametro);
            if (true !== $retorno) {
                echo $retorno;
                exit();
            }
        }
        return true;
    }
}
