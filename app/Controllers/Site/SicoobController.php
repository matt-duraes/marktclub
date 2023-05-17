<?php

namespace App\Controllers\Site;

use Controller\Controller;
use App\Models\Site\BannerModel;
use App\Models\Site\Sicoob\ParcelaModel;

final class SicoobController extends Controller
{
    public function index()
    {
        return view('sicoob.index', [
            'menu' => 'sicoob',
            'banner' => (new BannerModel())->sicoob()
        ]);
    }

    public function consignado()
    {
        return view('sicoob.consignado', [
            'menu' => 'sicoob',
            'banner' => (new BannerModel())->sicoob(),
            'dado' => (new ParcelaModel())->listarConsignado()
        ]);
    }

    public function creditoPessoal()
    {
        return view('sicoob.credito_pessoal', [
            'menu' => 'sicoob',
            'banner' => (new BannerModel())->sicoob(),
            'dado' => (new ParcelaModel())->listarCreditoPessoal()
        ]);
    }

    public function veiculoZero()
    {
        return view('sicoob.veiculo_zero', [
            'menu' => 'sicoob',
            'banner' => (new BannerModel())->sicoob(),
            'dado' => (new ParcelaModel())->listarVeiculoZero()
        ]);
    }

    public function veiculoSeminovo()
    {
        return view('sicoob.veiculo_seminovo', [
            'menu' => 'sicoob',
            'banner' => (new BannerModel())->sicoob(),
            'dado' => (new ParcelaModel())->listarVeiculoSeminovo()
        ]);
    }


    public function abrirModalRegulamento($url = null)
    {
        return view('sicoob.index.modalRegulamento', [
            'menu' => 'sicoob',
            'tipo' => $url
        ]);
    }
}
