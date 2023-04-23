<?php

namespace App\Controllers\Site;

use Controller\Controller;
use App\Models\Site\BannerModel;
use App\models\Site\Loja\NovaLojaModel;

final class IndexController extends Controller
{
    public function index()
    {
        return view('index', [
            'menu' => 'home',
            'loja_nova' => (new NovaLojaModel())->listarDados(),
            'banner' => (new BannerModel())->index(),
        ]);
    }
}
