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

        $empresa = sessao('CLUBE')->empresa;
        $url = $this->parceiroSamsungPorEmpresa($empresa);
        $dadosParceiro = (new BuscarModelLoja(url: $url))->buscarSamsung();

        return view('samsung', [
            'menu'            => 'samsung',
            'banner'          => (new BannerModel())->home(),
            'bannerFixo'      => (new BannerModel())->samsungCartao(),
            'link'            => $dadosUsuario->link,
            'email'           => $dadosUsuario->email,
            'pessoal'         => $dadosUsuario->pessoal,
            'trabalho'        => $dadosUsuario->trabalho,
            'linkArquivoSite' => $dadosParceiro[0] ?? '',
        ]);
    }

    /**
     * Verifica a empresa para pegar url do parceiro samsung específico
     *
     * @param [string] $idEmpresa
     * @return string
     */
    private function parceiroSamsungPorEmpresa($idEmpresa): string
    {
        $parceiros = [
            '62c6e14371c10bf6ffb20325af002e7e' => 'samsung-digio',
            '89293cbf6b7375554590367784933803' => 'samsung-uberconta',
        ];

        if (array_key_exists($idEmpresa, $parceiros)) {
            return $parceiros[$idEmpresa];
        } else {
            return 'samsung';
        }
    }
}
