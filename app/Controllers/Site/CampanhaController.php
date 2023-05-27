<?php

namespace App\Controllers\Site;

use Controller\Controller;
use App\Models\Site\BannerModel;
use App\Models\Site\Loja\ListarModel;

final class CampanhaController extends Controller
{
    public function index()
    {
        return view('campanha.index', [
            'banner' => (new BannerModel())->saude(),
            'lista'        => (new ListarModel())->listarDados(),
            'tipo_campanha' => 2
        ]);
    }
}
