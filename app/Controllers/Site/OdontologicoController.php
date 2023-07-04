<?php

namespace App\Controllers\Site;

use Controller\Controller;
use App\Models\Site\BannerModel;

final class OdontologicoController extends Controller
{
    public function index()
    {
        return view('odontologico.index', [
            'menu'   => 'odontologico',
            'banner' => (new BannerModel())->odontologico(),
        ]);
    }
}
