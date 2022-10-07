<?php

namespace App\Controllers\Site;

use Controller\Controller;

final class LojaController extends Controller
{
    public function index()
    {
        return view('loja.index', [
            'menu' => 'loja',
            'banner' => true
        ]);
    }

    public function proxima()
    {
        return view('loja.proxima', [
            'menu' => 'loja-proxima'
        ]);
    }
}
