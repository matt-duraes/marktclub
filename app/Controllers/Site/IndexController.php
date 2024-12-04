<?php

namespace App\Controllers\Site;

use Erro\Excecao;
use Http\Response;
use Controller\Controller;
use App\Classes\ParceiroLoja\Ordem;
use App\Models\Site\Saude\HomeModel;
use App\Models\Site\Loja\FiltroModel;
use App\Models\Site\Loja\ListarModel;
use App\Classes\ParceiroLoja\TipoLoja;
use App\Models\Site\Comunicacao\BannerModel;

final class IndexController extends Controller
{
    /**
     * @return Response
     * @throws Excecao
     */
    public function index(): Response
    {
        $Filtro = new FiltroModel([]);
        return view('index', [
            'menu'           => 'home',
            'Busca'          => $Filtro,
            'banner'         => (new BannerModel())->home(),
            'plano_saude'    => (new HomeModel())->valor
        ]);
    }

    public function postBuscar()
    {
        $LojaFavorita = new ListarModel(
            Filtro: new FiltroModel([
                'quantidade' => 3,
                'favorito'   => 'sim'
            ])
        );
        $MaisUtilizada = new ListarModel(
            tipo: (new TipoLoja(TipoLoja::LOJA)),
            Filtro: new FiltroModel([
                'quantidade' => 3,
                'acessado'   => 'sim',
                'ordem'      => (new Ordem(Ordem::RANDOMICO))->valor()
            ])
        );
        $LojaNova = new ListarModel(
            tipo: (new TipoLoja(TipoLoja::LOJA)),
            Filtro: new FiltroModel([
                'quantidade' => 3,
                'ordem'      => (new Ordem(Ordem::MAIS_NOVO))->valor()
            ])
        );
        return mensagemSucesso([
            'favorito'   => $LojaFavorita->listarDados()->lista ?? [],
            'acessado'   => $MaisUtilizada->listarDados()->lista ?? [],
            'novo'       => $LojaNova->listarDados()->lista ?? [],
        ]);
    }
}
