<?php

namespace App\Controllers\Site;

use Controller\Controller;
use App\Models\Site\Samsung\BannerModel;
use App\Models\Site\Samsung\BuscarModel;

final class SamsungController extends Controller
{
    public function index()
    {
        $dado = (new BuscarModel())->buscar();
        return view('samsung', [
            'menu'     => 'samsung',
            'banner'   => (new BannerModel())->home(),
            'link'     => $dado->link,
            'email'    => $dado->email,
            'pessoal'  => $dado->pessoal,
            'trabalho' => $dado->trabalho,
        ]);
    }
}
