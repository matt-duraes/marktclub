<?php

namespace App\Controllers\Site;

use Erro\Excecao;
use Http\Response;
use Controller\Controller;
use App\Models\Site\BannerModel;
use App\Models\Site\Loja\NovaLojaModel;
use App\Models\Site\Loja\LojaModel;

final class IndexController extends Controller
{
    /**
     * @return Response
     * @throws Excecao
     */
    public function index($url = null): Response
    {
        if (sessaoExiste('TEMPLATE') && sessao('TEMPLATE') == 'melhor-idade') {
            return new Response(url: route('acessoRapido.index'));
        }
        $favoritas = (new LojaModel($url))->montarFavorito();
        return view('index', [
            'menu'      => 'home',
            'loja_nova' => (new NovaLojaModel())->listarDados(),
            'loja_favorita' => $favoritas,
            'banner'    => (new BannerModel())->index(),
        ]);
    }
}
