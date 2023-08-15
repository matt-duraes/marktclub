<?php

namespace App\Controllers\Site;

use Erro\Excecao;
use Http\Response;
use Modules\Botao;
use Modules\Inteiro;
use Controller\Controller;
use App\Models\Site\BannerModel;
use App\Classes\ParceiroLoja\Ordem;
use App\Models\Site\Loja\ListarModel;

final class IndexController extends Controller
{
    /**
     * @return Response
     * @throws Excecao
     */
    public function index(): Response
    {
        $MaisUtilizada = new ListarModel(
            quantidade: new Inteiro(3),
            acessado: new Botao(Botao::SIM)
        );
        $LojaNova = new ListarModel(
            quantidade: new Inteiro(3),
            ordem: new Ordem(Ordem::MAIS_NOVO)
        );
        $LojaFavorita = new ListarModel(
            quantidade: new Inteiro(3),
            favorito: new Botao(Botao::SIM),
            ordem: new Ordem(Ordem::RANDOMICO)
        );

        return view('index', [
            'menu'           => 'home',
            'mais_utilizada' => $MaisUtilizada->listarDados(),
            'loja_nova'      => $LojaNova->listarDados(),
            'loja_favorita'  => $LojaFavorita->listarDados(),
            'banner'         => (new BannerModel())->index(),
        ]);
    }
}
