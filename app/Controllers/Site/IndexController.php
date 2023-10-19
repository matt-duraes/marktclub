<?php

namespace App\Controllers\Site;

use Erro\Excecao;
use Http\Response;
use Controller\Controller;
use App\Classes\ParceiroLoja\Ordem;
use App\Models\Site\Saude\HomeModel;
use App\Models\Site\Loja\FiltroModel;
use App\Models\Site\Loja\ListarModel;
use App\Models\Site\Popup\PopupModel;
use App\Models\Site\Comunicacao\BannerModel;

final class IndexController extends Controller
{
    /**
     * @return Response
     * @throws Excecao
     */
    public function index(): Response
    {
        $popup = ((new PopupModel()))->buscarPopup();
        return view('index', [
            'popup' => $popup->dado[0] ?? '',
            'menu'           => 'home',
            'banner'         => (new BannerModel())->home(),
            'plano_saude'    => (new HomeModel())->valor
        ]);
    }

    public function postBuscar()
    {
        $MaisUtilizada = new ListarModel(
            Filtro: new FiltroModel([
                'quantidade' => 3,
                'acessado'   => 'sim'
            ])
        );
        $LojaNova = new ListarModel(
            Filtro: new FiltroModel([
                'quantidade' => 3,
                'ordem'      => (new Ordem(Ordem::MAIS_NOVO))->valor()
            ])
        );
        $LojaFavorita = new ListarModel(
            Filtro: new FiltroModel([
                'quantidade' => 3,
                'favorito'   => 'sim',
                'ordem'      => (new Ordem(Ordem::RANDOMICO))->valor()
            ])
        );
        return mensagemSucesso([
            'acessado'   => $MaisUtilizada->listarDados()->lista ?? [],
            'novo'       => $LojaNova->listarDados()->lista ?? [],
            'favorito'   => $LojaFavorita->listarDados()->lista ?? [],
        ]);
    }
}
