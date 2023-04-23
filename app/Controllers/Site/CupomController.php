<?php

namespace App\Controllers\Site;

use Http\Request;
use Http\Response;
use Helpers\ApiHelper;
use Controller\Controller;
use App\models\Site\Cupom\ListarModel;

final class CupomController extends Controller
{
    public function index(?string $pesquisa = null)
    {
        return view('cupom.index', [
            'menu' => 'cupom',
            'banner' => false,
            'pesquisa' => $pesquisa,
            'lista' => (new ListarModel())->listarDados(),
            'parceiroTipo' => 'cupom'
        ]);
    }

    public function buscar(Request $request, ?string $pesquisa = null)
    {
        if ($pesquisa) {
            return $this->index($pesquisa);
        }
        if (empty($request->pesquisa)) {
            return new Response(url: route('cupom.index'));
        }
        return new Response(url: route('cupom.buscar') . '/' . strSlug($request->pesquisa));
    }

    public function detalhe(string $url)
    {
        return view('cupom.detalhe', [
            'url' => $url
        ]);
    }
}
