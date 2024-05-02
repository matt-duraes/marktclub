<?php

namespace App\Controllers\Site;

use Erro\Excecao;
use Http\Response;
use Controller\Controller;
use App\Models\Site\BannerModel;
use App\Models\Site\Loja\ListarModel;
use App\Classes\ParceiroLoja\TipoLoja;

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
            tipo: new TipoLoja(TipoLoja::LOJA)
        );
        return view('turismo.index', [
            'menu'         => 'turismo',
            'banner'       => (new BannerModel())->turismo(),
            'carro'        => (new BannerModel())->turismoCarro(),
            'lista'        => $Listar->listarDados(),
            'tipo'         => 'turismo',
        ]);
    }
}
