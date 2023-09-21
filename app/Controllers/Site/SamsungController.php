<?php

namespace App\Controllers\Site;

use Controller\Controller;
use App\Models\Site\BannerModel;
use App\Models\Site\Samsung\BuscarModel;

final class SamsungController extends Controller
{
    public function index()
    {
        $dado = (new BuscarModel())->buscar();
        return view('samsung', [
            'menu'   => 'samsung',
            'banner' => (new BannerModel())->samsung(),
            'dado'   => $dado
        ]);
    }
}
