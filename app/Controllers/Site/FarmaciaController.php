<?php

namespace App\Controllers\Site;

use Helpers\ApiHelper;
use Controller\Controller;
use App\Models\Site\BannerModel;
use App\Models\Site\Farmacia\FarmaciaModel;

final class FarmaciaController extends Controller
{
    public function index()
    {
        return view('farmacia.index', [
            'menu' => 'farmacia',
            'banner' => (new BannerModel())->farmacia(),
            'lista'  => (new FarmaciaModel())->listarDados()
        ]);
    }
    public function detalhe(string $url)
    {
        $lista = (new FarmaciaModel())->buscarFarmacia($url);
        return view('farmacia.detalhe', [
            'loja' => $url,
            'banner' => (new BannerModel())->farmacia(),
            'lista' => $lista
        ]);
    }
    public function carteirinha()
    {
        $Api = new ApiHelper('carteirinha:buscar');

        $carteira = $Api->get('/carteirinha/5595203c-f7b1-4211-9981-bf09eb236b35')->object();

        return view('farmacia.carteirinha', [
            'carteira' => $carteira ?? null
        ]);
    }

    public function tabela()
    {
        return view('farmacia.carteirinha');
    }
}
