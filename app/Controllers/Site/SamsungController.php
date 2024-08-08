<?php

namespace App\Controllers\Site;

use Http\Request;
use Controller\Controller;
use App\Models\Site\Samsung\BannerModel;
use App\Models\Site\Samsung\BuscarModel;
use App\Models\Site\Loja\BuscarModel as BuscarModelLoja;

final class SamsungController extends Controller
{
    public function index(Request $request)
    {
        $dadosUsuario = (new BuscarModel())->buscar();
        $dadosParceiro = (new BuscarModelLoja(url: 'samsung'))->buscarDados();

        return view('samsung', [
            'menu'         => 'samsung',
            'banner'       => (new BannerModel())->home(),
            'bannerFixo'   => (new BannerModel())->samsungFixo(),
            'link'         => $dadosUsuario->link,
            'email'        => $dadosUsuario->email,
            'pessoal'      => $dadosUsuario->pessoal,
            'trabalho'     => $dadosUsuario->trabalho,
            'linkArquivoSite' => $dadosParceiro->arquivo[0]->arquivo ?? '',
        ]);
    }
}
