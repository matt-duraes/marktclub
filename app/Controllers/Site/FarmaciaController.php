<?php

namespace App\Controllers\Site;

use Controller\Controller;
use App\Models\Site\BannerModel;
use App\Models\Site\Farmacia\FarmaciaModel;

final class FarmaciaController extends Controller
{
    public function index()
    {
        return view('farmacia.index', [
            'menu'   => 'farmacia',
            'banner' => (new BannerModel())->farmacia(),
            'lista'  => (new FarmaciaModel())->listarDados()
        ]);
    }

    public function detalhe(string $url)
    {
        $dado = (new FarmaciaModel())->buscarDados($url);
        return view(
            'farmacia.detalhe',
            [
                'menu'  => 'farmacia',
                'dado'  => $dado,
                'tipo'  => 'farmacia',
                'lista' => (new FarmaciaModel())->relacionado($dado->id)
            ]
        );
    }

    public function carteirinha()
    {
        //     $Api = new ApiHelper('carteirinha:buscar');
        //     $carteira = $Api->get('/carteirinha/5595203c-f7b1-4211-9981-bf09eb236b35')->object();
        $carteira = (object) [
            'cpf'  => '07385666167',
            'nome' => 'Mateus'
        ];

        return view(
            'farmacia.carteirinha',
            [
                'carteira' => $carteira
            ]
        );
    }
}
