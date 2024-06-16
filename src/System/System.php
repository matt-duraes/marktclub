<?php

namespace System\System;

use Erro\Erro;
use Erro\Excecao;
use Http\Response;
use Route\Config as RouteConfig;
use Controller\ControllerInterface;

final class System
{
    private RouteConfig $route;
    private Middleware $middleware;
    private Request $request;
    private Controller $controller;
    private Response $retorno;
    private bool $middlewareErro = false;

    public function __construct()
    {
        $this->route = new RouteConfig();
        if (!$this->route->rotaUso()) {
            throw new Excecao(status: 404);
        }
        $this->adicionarIncludePadraoRota();
        $this->middleware = new Middleware($this->route);
        $this->executarMiddlewarePre();
        $this->request = new Request($this->route);
        $this->controller = new Controller($this->route, $this->request->request());
    }

    private function adicionarIncludePadraoRota()
    {
        $rota = mb_strtolower(ROTA_USO, 'UTF-8');
        if (!file_exists(ROOT . '/resources/php/' . $rota . '/autoload.php')) {
            return;
        }
        include ROOT . '/resources/php/' . $rota . '/autoload.php';
    }

    public function init(): Response
    {
        $this->executarController();
        $this->executarMiddlewarePos();
        return $this->retorno;
    }

    private function executarMiddlewarePre(): void
    {
        $retorno = $this->middleware->pre();
        if (true !== $retorno) {
            $this->middlewareErro = true;
            $this->retorno = $retorno;
        }
    }

    private function executarController(): void
    {
        if ($this->middlewareErro) {
            return;
        }
        $classe = $this->controller->classe();
        $metodo = $this->controller->metodo();
        $parametro = $this->controller->parametro();
        if (!$classe instanceof ControllerInterface) {
            throw new Erro(
                mensagem: 'Instância incorreta.',
                titulo: 'Classe sem Interface.',
                texto: 'A classe <strong>' . get_class($classe)
                    . '</strong> não foi implementada ao contrato <strong>Controller\ControllerInterface</strong>.',
                sugestao: [
                    'Você deve extender a class abstrata <strong>Controller\controller</strong> em
                        sua classe <strong>' . get_class($classe) . '</strong>.',
                    'Você deve implementar a interface <strong>Controller\ControllerInterface</strong> a
                        classe <strong>' . get_class($classe) . '</strong>.'
                ]
            );
        }
        $this->retorno = call_user_func_array([$classe, $metodo], $parametro);
    }

    private function executarMiddlewarePos(): void
    {
        if ($this->middlewareErro) {
            return;
        }
        $retorno = $this->middleware->pos();
        if (true !== $retorno) {
            $this->middlewareErro = true;
            $this->retorno = $retorno;
        }
    }
}
