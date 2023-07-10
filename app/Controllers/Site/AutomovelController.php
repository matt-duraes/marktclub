<?php

namespace App\Controllers\Site;

use App\Models\Site\Automovel\ModeloModel;
use App\Models\Site\Automovel\MontadoraModel;
use App\Models\Site\Automovel\VeiculoModel;
use App\Models\Site\BannerModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Response;

final class AutomovelController extends Controller
{
    /**
     * @return Response
     * @throws Excecao
     */
    public function index(): Response
    {
        return view('automovel.index', [
            'menu'   => 'automovel',
            'lista'  => (new MontadoraModel())->listarDados(),
            'banner' => (new BannerModel())->automovel()
        ]);
    }

    /**
     * @param string $url
     *
     * @return Response
     * @throws Excecao
     */
    public function veiculo(string $url): Response
    {
        return view(
            'automovel.veiculo',
            [
                'menu'  => 'automovel',
                'lista' => (new VeiculoModel())->listarDados(),
            ]
        );
    }

    /**
     * @param string $montadora
     * @param string $veiculo
     *
     * @return Response
     * @throws Excecao
     */
    public function modelo(string $montadora, string $veiculo): Response
    {
        return view(
            'automovel.modelo',
            [
                'menu'  => 'automovel',
                'lista' => (new ModeloModel())->listarDados()
            ]
        );
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function abrirModalModeloVoucher($url = null): Response
    {
        return view('automovel.detalheAutomovel.modalVoucher');
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function abrirModalModeloDeclaracao($url = null): Response
    {
        $perfil = 'titular';
        $default = $perfil == 'titular' ? '' : 'esconde';
        $esconde = $perfil == 'dependente' ? '' : 'esconde';

        return view('automovel.detalheAutomovel.modalDeclaracao', [
            'perfil'  => 'titular',
            'default' => $default,
            'esconde' => $esconde
        ]);
    }
}
