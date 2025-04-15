<?php

namespace App\Controllers\Site;

use App\Models\Site\Comunicacao\BannerModel;
use App\Models\Site\Loja\BuscarModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Response;

class LgController extends Controller
{
    /**
     * @return Response
     * @throws Excecao
     */
    public function index(): Response
    {
        return view('lg.index', [
            'menu'     => 'lg',
            'banner'   => (new BannerModel())->lg(),
            'tipo'     => 'lg',
            'parceiro' => (new BuscarModel('f3b02301dc3b53c2c51f194d2a2ad00c'))->buscarDados()
        ]);
    }
}
