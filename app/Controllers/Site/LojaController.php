<?php

namespace App\Controllers\Site;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Site\Loja\BuscaModel;

final class LojaController extends Controller
{
    public function index(Request $request, ?BuscaModel $Busca = null)
    {
        return view('loja.index', [
            'menu' => 'loja',
            'banner' => true,
            'Busca' => $Busca instanceof BuscaModel ? $Busca : new BuscaModel($request),
            'parceiro' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 1, 1, 1]
        ]);
    }

    public function busca(Request $request, ?string $pesquisa = null)
    {
        $Busca = new BuscaModel($request, $pesquisa);
        if ($pesquisa) {
            return $this->index($request, $Busca);
        }
        return new Response(url: $Busca->url());
    }

    public function detalhe(Request $request, $url)
    {
        return view('loja.detalhe', [
            'menu' => 'loja',
            'Busca' => new BuscaModel($request)
        ]);
    }

    public function proxima()
    {
        return view('loja.proxima', [
            'menu' => 'loja-proxima'
        ]);
    }
}
