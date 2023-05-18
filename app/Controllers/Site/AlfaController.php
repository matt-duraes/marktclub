<?php

namespace App\Controllers\Site;

use Controller\Controller;
use App\Models\Site\BannerModel;
use App\Models\Site\Credito\CreditoModel;

final class AlfaController extends Controller
{
    public function credito()
    {
        return view(
            'alfa.credito',
            [
                'menu' => 'credito',
                'banner' => (new BannerModel())->alfa()
            ]
        );
    }

    public function portabilidade()
    {
        return view(
            'alfa.portabilidade',
            [
                'menu' => 'credito',
                'banner' => (new BannerModel())->alfa(),
                'parcela' => (new CreditoModel())->listarParcelas()
            ]
        );
    }

    public function consignado()
    {
        return view(
            'alfa.consignado',
            [
                'menu' => 'credito',
                'banner' => (new BannerModel())->alfa(),
                'parcela' => (new CreditoModel())->listarParcelas()
            ]
        );
    }

    public function veiculo()
    {
        return view(
            'alfa.veiculo',
            [
                'menu' => 'credito',
                'parceiro' => [1, 2, 3]
            ]
        );
    }

    public function corretoraAlfa()
    {
        return view(
            'alfa.corretora',
            [
                'banner' => (new BannerModel())->corretora(),
                'consultoria' => (new BannerModel())->consultoriaAlfa(),
                'menu' => 'corretora_alfa'
            ]
        );
    }

    public function consultoriaAlfa()
    {
        return view(
            'alfa.consultoria',
            [
                'banner' => (new BannerModel())->consultoriaAlfa(),
                'menu' => 'corretora_alfa'
            ]
        );
    }
}
