<?php

namespace App\Controllers\Site;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Site\BannerModel;
use App\Models\Site\Loja\MapaModel;
use App\Models\Site\Loja\BuscaModel;
use App\models\Site\Loja\ListarModel;
use App\models\Site\Loja\RelacionadoModel;

final class LojaController extends Controller
{
    public function index(Request $request, ?BuscaModel $Busca = null)
    {
        return view('loja.index', [
            'menu' => 'loja',
            'banner' => true,
            'Busca' => $Busca instanceof BuscaModel ? $Busca : new BuscaModel($request),
            'lista' => (new ListarModel())->listarDados(),
            'parceiroTipo' => 'loja',
            'banner' => (new BannerModel())->loja(),
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

    public function detalhe(Request $request, $url = null, ?BuscaModel $Busca = null)
    {
        return view('loja.detalhe', [
            'menu' => 'loja',
            'url' => $url,
            'Busca' => $Busca instanceof BuscaModel ? $Busca : new BuscaModel($request),
            'lista' => (new RelacionadoModel())->listarDados(),
            'parceiroTipo' => 'loja'
        ]);
    }
    public function confirmar(string $url)
    {
        return view('loja.confirmar');
    }

    public function proxima(Request $request, ?MapaModel $Busca = null)
    {
        return view('loja.proxima', [
            'menu' => 'loja-proxima'
        ]);
    }

    public function abrirModal(Request $request)
    {
        return view('loja.geral.modal');
    }

    public function abrirModalIndicacao()
    {
        return view('loja.geral.modalIndicacao');
    }
    public function abrirMapaModal()
    {
        return view('loja.proxima.modal', [
            'menu' => 'loja-proxima'
        ]);
    }

    public function postBuscaMapa(Request $request)
    {
        $Busca = new MapaModel($request);

        return new Response(status: 201);
    }
}
