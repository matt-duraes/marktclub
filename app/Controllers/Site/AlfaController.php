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
        return view('alfa.portabilidade', [
            'menu' => 'credito',
            'banner' => (new BannerModel())->alfa(),
            'parcela' => (new CreditoModel())->listarParcelas()
        ]);
    }

    public function consignado()
    {
        $taxa = [
            '' => 'Escolha uma opção',
            "12" => "01 a 12 vezes - 1,65% a.m. ",
            "24" => "13 a 24 vezes - 1,62% a.m. ",
            "36" => "25 a 36 vezes - 1,60% a.m. ",
            "48" => "37 a 48 vezes - 1,60% a.m. ",
            "60" => "49 a 60 vezes - 1,61% a.m. ",
            "72" => "61 a 72 vezes - 1,61% a.m. ",
            "84" => "73 a 84 vezes - 1,62% a.m. ",
            "96" => "85 a 96 vezes - 1,64% a.m. ",
        ];

        return view('alfa.consignado', [
            'menu' => 'credito',
            'taxa' => $taxa
        ]);
    }

    public function veiculo()
    {
        return view('alfa.veiculo', [
            'menu' => 'credito',
            'parceiro' => [1, 2, 3]
        ]);
    }

    public function corretoraAlfa()
    {
        return view('alfa.corretora', [
            'banner' => (new BannerModel())->corretora(),
            'menu' => 'corretora_alfa'
        ]);
    }

    public function consultoriaAlfa()
    {
        return view('alfa.consultoria');
    }
}
