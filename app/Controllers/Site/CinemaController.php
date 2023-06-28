<?php

namespace App\Controllers\Site;

use App\Models\Site\BannerModel;
use App\Models\Site\Cinema\ListarModel;

use Controller\Controller;

final class CinemaController extends Controller
{
    /**
     * @return Response
     * @throws Excecao
     */
    public function index(?string $pesquisa = null)
    {
        return view('cinema.index', [
            'menu'   => 'cinema',
            'banner' => (new BannerModel())->cinema(),
            'lista' => (new ListarModel())->listarDados(),
            'parceiroTipo' => 'cinema',
        ]);
    }

    public function extrato()
    {
        return view('cinema.extrato');
    }
}
