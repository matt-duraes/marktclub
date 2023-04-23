<?php

namespace App\Controllers\Site;

use Controller\Controller;
use App\Models\Site\BannerModel;
use App\models\Site\Automovel\ModeloModel;
use App\models\Site\Automovel\VeiculoModel;
use App\models\Site\Automovel\MontadoraModel;

final class AutomovelController extends Controller
{
    public function index()
    {
        return view('automovel.index', [
            'menu' => 'automovel',
            'lista' => (new MontadoraModel())->listarDados(),
            'banner' => (new BannerModel())->automovel()
        ]);
    }
    public function veiculo(string $url)
    {
        return view('automovel.veiculo', [
            'menu' => 'automovel',
            'lista' => (new VeiculoModel())->listarDados(),
        ]);
    }

    public function modelo(string $montadora, string $veiculo)
    {
        return view('automovel.modelo', [
            'menu' => 'automovel',
            'lista' => (new ModeloModel())->listarDados()
        ]);
    }


    public function abrirModalModeloVoucher($url = null)
    {
        return view('automovel.detalheAutomovel.modalVoucher');
    }

    public function abrirModalModeloDeclaracao($url = null)
    {
        $perfil = 'titular';
        $default = $perfil == 'titular' ? '' : 'esconde';
        $esconde = $perfil == 'dependente' ? '' : 'esconde';

        return view('automovel.detalheAutomovel.modalDeclaracao', [
            'perfil' => 'titular',
            'default' => $default,
            'esconde' => $esconde
        ]);
    }
}
