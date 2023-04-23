<?php

namespace App\Controllers\Site;

use Controller\Controller;
use App\Models\Site\BannerModel;

final class SicoobController extends Controller
{
    public function index()
    {
        return view('sicoob.index', [
            'menu' => 'sicoob',
            'banner' => (new BannerModel())->sicoob()
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
