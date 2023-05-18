<?php

namespace App\Controllers\Site;

use Erro\Excecao;
use Http\Response;
use Controller\Controller;
use App\Models\Site\BannerModel;
use App\Models\Site\Loja\NovaLojaModel;

final class IndexController extends Controller
{
    /**
     * @return Response
     * @throws Excecao
     */
    public function index(): Response
    {
        if (sessaoExiste('TEMPLATE') && sessao('TEMPLATE') == 'melhor-idade') {
            return new Response(url: route('acessoRapido.index'));
        }
        return view('index', [
            'menu'      => 'home',
            'loja_nova' => (new NovaLojaModel())->listarDados(),
            'banner'    => (new BannerModel())->index()
        ]);
    }
}
