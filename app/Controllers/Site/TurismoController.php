<?php

namespace App\Controllers\Site;

use Erro\Excecao;
use Http\Response;
use Modules\Inteiro;
use Controller\Controller;
use App\Models\Site\BannerModel;
use App\Classes\ParceiroLoja\Tipo;
use App\Models\Site\Loja\ListarModel;

final class TurismoController extends Controller
{
    /**
     * @return Response
     * @throws Excecao
     */
    public function index(): Response
    {
        $Listar = new ListarModel(
            quantidade: new Inteiro(3),
            tipo: new Tipo(Tipo::LOJA)
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
