<?php

namespace Route;

final class Config
{
    private array $rotaUso;
    private string $rota;
    private string $controller;
    private string $action;
    private array $explode;

    public function __construct()
    {
        $this->montarExplode();
        $this->pegarControllerAction();
        $this->verificarSeRotaBloqueada();
        $this->pegarRotaUso();

        define('ROUTE_DIRETORIO', $this->rota);
    }

    private function montarExplode(): void
    {
        $uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

        $explode = explode('/', $uri);
        if (current($explode) == null) {
            array_shift($explode);
        }
        if (end($explode) == null) {
            array_pop($explode);
        }

        if (count($explode) == 0) {
            $explode = ['', ''];
        } elseif (count($explode) == 1) {
            $explode[] = '';
        }

        $this->explode = $explode;
        return;
    }

    private function pegarControllerAction(): void
    {
        $explode = $this->explode;

        $controller = $explode[0];
        $action = $explode[1];
        $rota = ucfirst(mb_strtolower(env('ROTA_PRINCIPAL', 'site'), 'UTF-8'));
        $rota = !empty($rota) ? $rota : 'site';

        $expressaoRota = '/^' . preg_replace('/[^A-Za-z]/', '', $controller) . '$/i';

        $diretorioRota = $this->transformarRotaEmDiretorio($rota);
        if (preg_grep($expressaoRota, $diretorioRota)) {
            unset($explode[0]);
            $rota = ucfirst(mb_strtolower($controller, 'UTF-8'));
            $controller = $action;
            $action = isset($explode[2]) && !empty($explode[2]) ? $explode[2] : 'index';
        }

        $this->rota = $rota;
        $this->controller = !empty($controller) ? $controller : 'index';
        $this->action = !empty($action) ? $action : 'index';
    }
    private function transformarRotaEmDiretorio($rota): array
    {
        $lista = array_diff(scandir(__DIR__ . '/../../routes'), ['..', '.']);
        $retorno = [];
        foreach ($lista as $nome) {
            $retorno[] = mb_strtolower(preg_replace(
                ['/Route.php$/', '/([A-Z])/', '/^\_/'],
                ['', '_$1', ''],
                $nome
            ), 'UTF-8');
        }
        return array_diff($retorno, [mb_strtolower($rota, 'UTF-8')]);
    }

    private function verificarSeRotaBloqueada(): void
    {
        $rota = mb_strtolower($this->rota, 'UTF-8');
        $rotaBloqueada = env('ROTA_BLOQUEADA', '');

        if (
            (!empty($rotaBloqueada) && is_array($rotaBloqueada) && in_array($rota, $rotaBloqueada)) ||
            (!empty($rotaBloqueada) && is_string($rotaBloqueada) && $rotaBloqueada == $rota)
        ) {
            mensagemStatus(404);
        }
    }

    private function pegarRotaUso(): void
    {
        require_once ROOT . '/routes/' . $this->rota . 'Route.php';
        $this->rotaUso = Route::pegarRota($this->controller, $this->action);
    }

    public function rota()
    {
        return $this->rota;
    }

    public function controller()
    {
        return $this->controller;
    }

    public function action()
    {
        return $this->action;
    }

    public function rotaUso()
    {
        return $this->rotaUso;
    }
}
