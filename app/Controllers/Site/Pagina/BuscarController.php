<?php

namespace App\Controllers\Site\Pagina;

use Http\Request;
use Controller\Controller;
use App\Models\Site\Pagina\HashModel;
use App\Models\Site\Pagina\BuscarModel;

final class BuscarController extends Controller
{
    public function index(Request $request)
    {
        $uri = explode('/', preg_replace('/^\//', '', URI))[0];
        return $this->buscarApi($uri, $request);
    }

    public function buscar(Request $request, string $uri)
    {
        return $this->buscarApi($uri, $request);
    }

    public function manole()
    {
        $uri = 'manole';
        $dado = [''];
        $Html = new BuscarModel(
            uri: $uri
        );
        return view('pagina', [
            'html' => $Html->html,
            'dado' => base64Encode($dado, url: true),
            'url'  => 'manole',
        ]);
    }

    private function buscarApi(string $uri, $request)
    {
        $Html = new BuscarModel(
            uri: $uri
        );

        return view('pagina', [
            'html' => $Html->html,
            'dado' => base64Encode($request->dado(), url: true),
            'url'  => $uri,
        ]);
    }

    public function postBuscar(Request $request)
    {
        $Api = new HashModel(
            hash: $request->hash,
            replace: $request->dado
        );
        return mensagemSucesso(dado: $Api->retorno, status: 201);
    }
}
