<?php

namespace App\Controllers\Site;

use App\Models\Site\BannerModel;
use App\Models\Site\Loja\NovaLojaModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Response;

final class IndexController extends Controller
{
    /**
     * @return Response
     * @throws Excecao
     */
    public function index(): Response
    {
        return view('index', [
            'menu'      => 'home',
            'loja_nova' => (new NovaLojaModel())->listarDados(),
            'banner'    => (new BannerModel())->index()
        ]);
    }
}
