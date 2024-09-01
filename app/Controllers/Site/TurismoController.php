<?php

namespace App\Controllers\Site;

use Erro\Excecao;
use Http\Response;
use Controller\Controller;
use App\Models\Site\Loja\FiltroModel;
use App\Models\Site\Loja\ListarModel;
use App\Classes\ParceiroLoja\TipoLoja;
use App\Models\Site\Comunicacao\BannerModel;

final class TurismoController extends Controller
{
    /**
     * @return Response
     * @throws Excecao
     */
    public function index(): Response
    {
        $Listar = new ListarModel(
            quantidade: 3,
            tipo: new TipoLoja(TipoLoja::LOJA),
            Filtro: new FiltroModel([
                'categoria'    => 'outros',
                'subcategoria' => '2a13da6ab3ac2b233bb0dc7f57e3ac49'
            ])
        );
        return view('turismo.index', [
            'menu'         => 'turismo',
            'banner'       => (new BannerModel())->turismo(),
            'lista'        => $Listar->listarDados(),
            'tipo'         => 'turismo',
        ]);
    }

    public function redirecionar()
    {
    }
}
