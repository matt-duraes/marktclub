<?php

namespace App\Controllers\Site;

use App\Models\Site\BannerModel;

use Controller\Controller;

final class CinemaController extends Controller
{
    public function index(?string $pesquisa = null)
    {
        return view('cinema.index', [
            'menu'   => 'cinema',
            'banner' => (new BannerModel())->cinema()
        ]);
    }

    public function extrato()
    {
        return view('cinema.extrato');
    }
}
