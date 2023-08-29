<?php

namespace App\Controllers\Site;

use Erro\Excecao;
use Http\Response;
use Modules\Botao;
use Modules\Inteiro;
use Controller\Controller;
use App\Classes\ParceiroLoja\Ordem;
use App\Models\Site\Saude\HomeModel;
use App\Models\Site\Loja\ListarModel;
use App\Models\Site\Comunicacao\BannerModel;

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
            'banner'         => (new BannerModel())->home(),
            'plano_saude'    => (new HomeModel())->valor
        ]);
    }
}
