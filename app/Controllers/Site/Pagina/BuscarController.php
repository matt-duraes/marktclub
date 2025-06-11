<?php

namespace App\Controllers\Site\Pagina;

use Controller\Controller;
use App\Models\Site\Pagina\BuscarModel;

final class BuscarController extends Controller
{
    public function index()
    {
        $uri = explode('/', preg_replace('/^\//', '', $_SERVER['REQUEST_URI']))[0];
        return $this->buscarApi($uri);
    }
    public function buscar(string $uri)
    {
        return $this->buscarApi($uri);
    }
    private function buscarApi(string $uri)
    {
        $Html = new BuscarModel($uri);
        return view('pagina', [
            'html' => $Html->html,
            'url'  => 'unimed-natal',
        ]);
    }
}
