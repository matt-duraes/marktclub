<?php

namespace System\System;

use Erro\Excecao;
use Http\Request;
use Route\Config as RouteConfig;
use Controller\ControllerInterface;

final class Controller
{
    private ControllerInterface $classe;
    private string $metodo;
    private array $uri;
    protected array $parametro;

    public function __construct(
        private RouteConfig $route,
        private Request $request
    ) {
        $this->validarControllerExiste();
        $this->validarMetodoExiste();
        $this->validarActionExiste();
        $this->validarParametro();
        $this->pegarParametro();
    }

    private function validarControllerExiste(): void
    {
        $classe = $this->route->rotaUso()['controller'] ?? '';
        if (empty($classe) || !class_exists($classe)) {
            throw new Excecao(status: 404);
        }
        $this->classe = new $classe();
    }

    private function validarMetodoExiste(): void
    {
        $metodoHttpRota = $this->route->rotaUso()['metodo'];
        $metodoHttpRequest = $this->request->metodo();

        define('VIEW', $metodoHttpRota == 'VIEW');

        $action = $this->route->rotaUso()['action'];
        if ($metodoHttpRota == 'VIEW' && $metodoHttpRequest == 'GET') {
            $metodo = $action;
        } elseif ($metodoHttpRota == 'GET' && $metodoHttpRequest == 'GET') {
            $metodo = 'get' . ucfirst($action);
        } elseif ($metodoHttpRota == 'POST' && $metodoHttpRequest == 'POST') {
            $metodo = 'post' . ucfirst($action);
        } elseif ($metodoHttpRota == 'PUT' && $metodoHttpRequest == 'PUT') {
            $metodo = 'put' . ucfirst($action);
        } elseif ($metodoHttpRota == 'DELETE' && $metodoHttpRequest == 'DELETE') {
            $metodo = 'delete' . ucfirst($action);
        } else {
            throw new Excecao(status: 404);
        }
        $this->metodo = $metodo;
    }

    private function validarActionExiste(): void
    {
        if (!method_exists($this->classe, $this->metodo)) {
            throw new Excecao(status: 404);
        }
    }

    public function validarParametro(): void
    {
        $rotaUso = $this->route->rotaUso();

        $requestUri = explode('/', preg_replace('/^\//', '', $this->request->uri()));
        $rotaUri = explode('/', preg_replace('/^\//', '', $rotaUso['uri']));

        $rotaDiretorio = mb_strtolower(ROUTE_DIRETORIO, 'UTF-8');
        $rotaPrincipal = env('ROTA_PRINCIPAL', '');
        if (empty($rotaPrincipal)) {
            $rotaPrincipal = 'site';
        }

        if ($rotaPrincipal != $rotaDiretorio && $rotaDiretorio == $requestUri[0]) {
            unset($requestUri[0]);
            $requestUri = array_values($requestUri);
        }

        $rotaUri = array_values($rotaUri);
        $requestUri = array_values($requestUri);

        $controller = $rotaUri[0];
        $action = $rotaUri[1] ?? '';

        $controllerParametro = $this->uriEUmParametro($controller);
        $actionParametro = $this->uriEUmParametro($action);

        if (!$controllerParametro || empty($controller)) {
            unset($requestUri[0], $rotaUri[0]);
        }
        if (!$actionParametro || empty($action)) {
            unset($requestUri[1], $rotaUri[1]);
        }

        $uriOpcional = 0;
        if ($rotaUri) {
            $rotaUriTemp = [];
            foreach ($rotaUri as $val) {
                if (preg_match("/^\{\![a-zA-Z0-9\_]+\}$/", $val)) {
                    $uriOpcional++;
                }
                $rotaUriTemp[] = preg_replace("/[^a-zA-Z0-9\_]/", '', $val);
            }
            $rotaUri = $rotaUriTemp;
        }

        $uriAtual = count($requestUri);
        $uriMaxima = count($rotaUri);
        $uriMinima = $uriMaxima - $uriOpcional;
        if ($uriAtual > $uriMaxima || $uriAtual < $uriMinima) {
            mensagemStatus(404);
        }
        $uriFinal = [];
        if ($requestUri) {
            $i = 0;
            $Purifier = new \HTMLPurifier();
            foreach ($requestUri as $val) {
                $uriFinal[$rotaUri[$i]] = $Purifier->purify(strip_tags(urldecode($val)));
                $i++;
            }
        }
        $this->uri = $uriFinal;
    }

    private function uriEUmParametro($valor): bool
    {
        $exp = "/^\{[a-zA-Z0-9_]+\}$/";
        return preg_match($exp, $valor);
    }

    public function pegarParametro(): void
    {
        $parametro = $this->uri;
        $ReflectionMethod = new \ReflectionMethod($this->classe, $this->metodo);
        $parametroDeclarado = $ReflectionMethod->getParameters();
        foreach ($parametroDeclarado as $r) {
            if ($r->name == 'Request' && $parametro) {
                $parametro = array_merge(['Request' => $this->request], $parametro);
            } elseif ($r->name == 'request' && $parametro) {
                $parametro = array_merge(['request' => $this->request], $parametro);
            } elseif ($r->name == 'Request') {
                $parametro = ['Request' => $this->request];
            } elseif ($r->name == 'request') {
                $parametro = ['request' => $this->request];
            }
        }
        $this->parametro = $parametro;
    }

    public function classe(): ControllerInterface
    {
        return $this->classe;
    }

    public function metodo(): string
    {
        return $this->metodo;
    }

    public function parametro(): array
    {
        return $this->parametro;
    }
}
