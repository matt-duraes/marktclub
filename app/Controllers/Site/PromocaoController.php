<?php

namespace App\Controllers\Site;

use Controller\Controller;

final class PromocaoController extends Controller
{
    public function index()
    {
        return view('promocao.index', [
            'menu' => 'promocao',
            'tituloPagina' => 'Promoções',
            'lista' => [1,2,3,4,5,6]
        ]);
    }
}
