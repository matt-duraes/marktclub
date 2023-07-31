<?php

namespace App\Controllers\Site;

use Erro\Excecao;
use Http\Response;
use Modules\Botao;
use Modules\Inteiro;
use Controller\Controller;
use App\Models\Site\BannerModel;
use App\Classes\ParceiroLoja\Ordem;
use App\Helpers\ClubeApiHelper;
use App\Models\Site\Loja\ListarModel;

final class IndexController extends Controller
{
    /**
     * @return Response
     * @throws Excecao
     */
    public function index(): Response
    {
        $LojaNova = new ListarModel(
            quantidade: new Inteiro(3),
            ordem: new Ordem(Ordem::MAIS_NOVO)
        );
        $LojaFavorita = new ListarModel(
            quantidade: new Inteiro(3),
            favorito: new Botao(Botao::SIM),
            ordem: new Ordem(Ordem::RANDOMICO)
        );
        $promocoes = ((new ClubeApiHelper()))
        ->json([
            'tipo'       => 'promocao',
            'pagina'     => 1,
            'quantidade' => 2,
        ])
        ->get('/publicidade')
        ->object();

        return view('index', [
            'menu'          => 'home',
            'loja_nova'     => $LojaNova->listarDados(),
            'loja_favorita' => $LojaFavorita->listarDados(),
            'banner'        => (new BannerModel())->index(),
            'promocoes'     => $promocoes->dado->lista
        ]);
    }

    // public function getPromocoes(): Response
    // {
    //     $dado = ((new ClubeApiHelper()))
    //     ->json([
    //         'tipo'       => 'promocao',
    //         'pagina'     => 1,
    //         'quantidade' => 2,
    //     ])
    //     ->get('/publicidade')
    //     ->object();

    //     return mensagemSucesso($dado);
    // }
}
